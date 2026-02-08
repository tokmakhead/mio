<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['invoice', 'customer'])->latest('paid_at');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('invoice', function ($q) use ($search) {
                        $q->where('number', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('paid_at', [$request->start_date, $request->end_date]);
        }

        $payments = $query->paginate(10);

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Invoice $invoice)
    {
        return view('payments.create', compact('invoice'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01', 'max:' . $invoice->remaining_amount],
            'method' => ['required', 'string'],
            'paid_at' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        $payment = $invoice->payments()->create([
            'customer_id' => $invoice->customer_id,
            'amount' => $validated['amount'],
            'currency' => $invoice->currency,
            'method' => $validated['method'],
            'paid_at' => $validated['paid_at'],
            'note' => $validated['note'],
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Ödeme başarıyla eklendi.');
    }
}
