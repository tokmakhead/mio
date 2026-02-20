<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $body;
    public string $renderedSubject;
    public bool $useTemplate;
    public string $brand_color;
    public string $brand_logo;
    public string $brand_name;

    public function __construct(public Invoice $invoice)
    {
        $template = EmailTemplate::where('type', 'invoice')->where('enabled', true)->first();
        $settings = \App\Models\SystemSetting::first();
        $brand = \App\Models\BrandSetting::all()->pluck('value', 'key');

        $this->brand_color = $brand['primary_color'] ?? '#dc2626';
        $this->brand_logo = $brand['logo_path'] ?? ($settings->logo_path ?? '');
        $this->brand_name = $brand['site_title'] ?? ($settings->site_name ?? 'MIONEX');

        if ($template) {
            $vars = [
                'customer_name' => $invoice->customer->name ?? 'Müşteri',
                'invoice_number' => $invoice->number,
                'invoice_date' => $invoice->issue_date ? $invoice->issue_date->format('d.m.Y') : now()->format('d.m.Y'),
                'due_date' => $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-',
                'grand_total' => number_format((float) $invoice->grand_total, 2) . ' ' . $invoice->currency,
                'brand_name' => $this->brand_name,
                'brand_logo' => $this->brand_logo,
                'brand_color' => $this->brand_color,
                'app_name' => $settings->site_name ?? 'MIONEX',
                'app_url' => config('app.url'),
            ];
            $this->body = $template->render($vars);
            $this->renderedSubject = $template->renderSubject($vars);
            $this->useTemplate = true;
        } else {
            $this->body = '';
            $this->renderedSubject = 'Fatura: ' . $invoice->number;
            $this->useTemplate = false;
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->renderedSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: $this->useTemplate ? 'emails.dynamic' : 'emails.invoice',
            with: [
                'body' => $this->body,
                'brand_color' => $this->brand_color,
                'brand_logo' => $this->brand_logo,
                'brand_name' => $this->brand_name,
            ],
        );
    }

    public function attachments(): array
    {
        $pdfService = app(\App\Services\InvoicePdfService::class);
        $data = $pdfService->prepareData($this->invoice);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.premium', $data);

        return [
            \Illuminate\Mail\Mailables\Attachment::fromData(
                fn() => $pdf->output(),
                $this->invoice->number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
