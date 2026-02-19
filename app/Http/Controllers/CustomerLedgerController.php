<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerLedgerController extends Controller
{
    /**
     * Display the customer ledger.
     */
    public function show(Customer $customer)
    {
        // 1. Get ALL entries sorted by date
        $allEntries = $customer->ledgerEntries()
            ->with(['ref']) // Load relationships
            ->orderBy('occurred_at')
            ->orderBy('id')
            ->get();

        // 2. Group by currency
        $groupedEntries = $allEntries->groupBy('currency');

        // 3. Calculate running balance for EACH currency group independently
        $groupedEntries->transform(function ($entries, $currency) {
            $balance = 0;
            foreach ($entries as $entry) {
                if ($entry->type === 'debit') {
                    $balance += $entry->amount;
                } else {
                    $balance -= $entry->amount;
                }
                $entry->balance = $balance; // Dynamically assign balance for view
            }
            return $entries;
        });

        // 4. Calculate Total Balances for Summary (e.g. ['TRY' => 100, 'USD' => 50])
        $summaryBalances = $groupedEntries->map(function ($entries) {
            return $entries->last()->balance ?? 0;
        });

        // 5. Get distinct currencies for tabs (sort TRY first if exists)
        $currencies = $groupedEntries->keys()->sort(function ($a, $b) {
            if ($a === 'TRY')
                return -1;
            if ($b === 'TRY')
                return 1;
            return strnatcmp($a, $b);
        });

        return view('customers.ledger', compact('customer', 'groupedEntries', 'summaryBalances', 'currencies'));
    }
}
