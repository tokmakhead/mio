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
        $entries = $customer->ledgerEntries()
            ->with('ref')
            ->orderBy('occurred_at')
            ->orderBy('id')
            ->get();

        // Calculate running balance
        $balance = 0;
        foreach ($entries as $entry) {
            if ($entry->type === 'debit') {
                $balance += $entry->amount;
            } else {
                $balance -= $entry->amount;
            }
            $entry->balance = $balance;
        }

        return view('customers.ledger', compact('customer', 'entries'));
    }
}
