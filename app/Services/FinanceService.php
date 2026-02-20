<?php

namespace App\Services;

use App\Models\LedgerEntry;
use Illuminate\Support\Facades\DB;

class FinanceService
{
    /**
     * Get balances for a specific customer grouped by currency.
     *
     * @param int $customerId
     * @return \Illuminate\Support\Collection
     */
    public function getCustomerBalances(int $customerId)
    {
        return LedgerEntry::where('customer_id', $customerId)
            ->select('currency')
            ->selectRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE -amount END) as balance')
            ->groupBy('currency')
            ->get()
            ->pluck('balance', 'currency');
    }

    /**
     * Get balances for all customers efficiently (optimized for list views).
     * Returns a collection where keys are customer IDs and values are arrays of currency balances.
     *
     * @param array $customerIds
     * @return \Illuminate\Support\Collection
     */
    public function getCustomersBalances(array $customerIds)
    {
        return LedgerEntry::whereIn('customer_id', $customerIds)
            ->select('customer_id', 'currency')
            ->selectRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE -amount END) as balance')
            ->groupBy('customer_id', 'currency')
            ->get()
            ->groupBy('customer_id')
            ->map(function ($group) {
                return $group->pluck('balance', 'currency');
            });
    }

    /**
     * Get detailed balances (debit, credit, balance) for multiple customers.
     *
     * @param array $customerIds
     * @return \Illuminate\Support\Collection
     */
    public function getCustomersDetailedBalances(array $customerIds)
    {
        return LedgerEntry::whereIn('customer_id', $customerIds)
            ->select('customer_id', 'currency')
            ->selectRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as debit')
            ->selectRaw('SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as credit')
            ->groupBy('customer_id', 'currency')
            ->get()
            ->groupBy('customer_id')
            ->map(function ($group) {
                return $group->keyBy('currency')->map(function ($row) {
                    return [
                        'debit' => (float) $row->debit,
                        'credit' => (float) $row->credit,
                        'balance' => (float) ($row->debit - $row->credit),
                    ];
                });
            });
    }

    /**
     * Get global financial summary (Receivables and Payables) grouped by currency.
     * This replaces inefficient PHP-side summation in controllers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getGlobalSummary()
    {
        // We sum by customer and currency first, then aggregate outcomes
        $customerBalances = LedgerEntry::select('customer_id', 'currency')
            ->selectRaw('SUM(CASE WHEN type = "debit" THEN amount ELSE -amount END) as balance')
            ->groupBy('customer_id', 'currency')
            ->having('balance', '<>', 0)
            ->get();

        $result = [];
        foreach ($customerBalances as $row) {
            $currency = $row->currency;
            if (!isset($result[$currency])) {
                $result[$currency] = ['receivable' => 0, 'payable' => 0, 'net' => 0];
            }

            if ($row->balance > 0) {
                $result[$currency]['receivable'] += (float) $row->balance;
            } else {
                $result[$currency]['payable'] += abs((float) $row->balance);
            }
            $result[$currency]['net'] += (float) $row->balance;
        }

        return collect($result);
    }

    /**
     * Format a currency value with its symbol using CurrencyService.
     * 
     * @param float $amount
     * @param string $currency
     * @return string
     */
    public function formatCurrency(float $amount, string $currency = 'TRY')
    {
        $symbol = (new CurrencyService())->getSymbol($currency);
        return $symbol . ' ' . number_format($amount, 2, ',', '.');
    }
}
