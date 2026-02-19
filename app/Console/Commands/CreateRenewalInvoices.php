<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateRenewalInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'services:create-renewals {--days=7 : Days before expiry to generate invoice}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate renewal invoices for expiring services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $targetDate = now()->addDays($days)->startOfDay();

        $this->info("Checking for services expiring on or before: {$targetDate->toDateString()}");

        // Find active services expiring within the window
        // We look for services expiring exactly on the target date to avoid spamming everyday
        // Or we can look for range, but need to check if invoice already exists.

        // Strategy: Look for services expiring in the next $days days.
        // Check if there is already a PENDING or PAID invoice covering the NEXT period.

        $services = Service::where('status', 'active')
            ->whereDate('end_date', '<=', $targetDate)
            ->whereDate('end_date', '>=', now()) // Don't process already expired (maybe? or allow late renewal)
            ->get();

        $count = 0;

        foreach ($services as $service) {
            // Calculate next period
            $nextStartDate = $service->end_date->copy()->addDay();
            $nextEndDate = $this->calculateNextEndDate($nextStartDate, $service->cycle);

            // Check if renewal invoice already exists for this service and start date
            // We look for any invoice item linked to this service where the invoice is not cancelled
            // And potentially check description or dates if we stored them. 
            // For now, simpler check: Has an invoice been created recently (e.g. in last 30 days) for this service?

            // Better check: Check if there is a draft/sent/paid invoice created AFTER the service start date 
            // that is linked to this service.

            $existingInvoice = Invoice::where('customer_id', $service->customer_id)
                ->where('status', '!=', 'cancelled')
                ->whereHas('items', function ($q) use ($service) {
                    $q->where('service_id', $service->id);
                })
                ->where('created_at', '>=', now()->subDays(30)) // Safety check to not overlap with old invoices
                ->exists();

            if ($existingInvoice) {
                $this->line("Skipping service #{$service->id} ({$service->name}) - Invoice already exists.");
                continue;
            }

            $this->createRenewalInvoice($service, $nextStartDate, $nextEndDate);
            $count++;
        }

        $this->info("Generated {$count} renewal invoices.");
        Log::info("Renewal Check: Generated {$count} invoices.");
    }

    protected function calculateNextEndDate(Carbon $startDate, string $cycle): Carbon
    {
        return match ($cycle) {
            'monthly' => $startDate->copy()->addMonth()->subDay(),
            'quarterly' => $startDate->copy()->addMonths(3)->subDay(),
            'yearly' => $startDate->copy()->addYear()->subDay(),
            'biennial' => $startDate->copy()->addYears(2)->subDay(),
            default => $startDate->copy()->addMonth()->subDay(), // Default to monthly
        };
    }

    protected function createRenewalInvoice(Service $service, Carbon $nextStart, Carbon $nextEnd)
    {
        DB::transaction(function () use ($service, $nextStart, $nextEnd) {
            $number = Invoice::generateNumber();

            $description = $service->name . ' (' . $nextStart->format('d.m.Y') . ' - ' . $nextEnd->format('d.m.Y') . ')';

            $invoice = Invoice::create([
                'customer_id' => $service->customer_id,
                'number' => $number,
                'status' => 'draft', // Always draft for review
                'issue_date' => now(),
                'due_date' => $service->end_date, // Due on expiry
                'currency' => $service->currency,
                'subtotal' => $service->price,
                'tax_total' => 0, // Simplified, should calculate
                'grand_total' => $service->price, // Simplified
                'notes' => 'Otomatik oluşturulan yenileme faturası.',
            ]);

            // Add Item
            // Note: Tax calculation logic should ideally use a service or helper
            // For now assuming price includes tax or 0 tax for simplicity in this command
            // You might want to fetch tax rate from service or settings
            $taxRate = 0; // Default or fetch from somewhere
            $taxAmount = $service->price * ($taxRate / 100);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'service_id' => $service->id,
                'description' => $description,
                'qty' => 1,
                'unit_price' => $service->price,
                'vat_rate' => $taxRate,
                'line_subtotal' => $service->price,
                'line_tax' => $taxAmount,
                'line_total' => $service->price + $taxAmount,
            ]);

            // Recalculate invoice totals including tax
            $invoice->subtotal = $service->price;
            $invoice->tax_total = $taxAmount;
            $invoice->grand_total = $service->price + $taxAmount;
            $invoice->save();

            $this->info("Created invoice {$number} for service: {$service->name}");

            // Optional: Notify admin or user
        });
    }
}
