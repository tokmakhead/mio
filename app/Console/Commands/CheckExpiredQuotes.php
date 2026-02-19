<?php

namespace App\Console\Commands;

use App\Models\Quote;
use Illuminate\Console\Command;

class CheckExpiredQuotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quotes:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired quotes and update their status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired quotes...');

        $expiredQuotes = Quote::where('valid_until', '<', now()->startOfDay())
            ->whereIn('status', ['draft', 'sent'])
            ->get();

        $count = 0;
        foreach ($expiredQuotes as $quote) {
            $quote->update(['status' => 'expired']);
            $quote->logActivity('expired', ['auto' => true]);
            $this->line("Quote #{$quote->number} mark as expired.");
            $count++;
        }

        $this->info("Done. {$count} quotes marked as expired.");
    }
}
