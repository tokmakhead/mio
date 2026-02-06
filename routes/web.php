<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customers resource
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);

    // Providers resource
    Route::resource('providers', \App\Http\Controllers\ProviderController::class);

    // Services resource
    Route::resource('services', \App\Http\Controllers\ServiceController::class);

    // Quotes resource
    Route::resource('quotes', \App\Http\Controllers\QuoteController::class);
    Route::get('quotes/{quote}/pdf', [\App\Http\Controllers\QuoteController::class, 'pdf'])->name('quotes.pdf');
    Route::post('quotes/{quote}/send', [\App\Http\Controllers\QuoteController::class, 'send'])->name('quotes.send');
    Route::post('quotes/{quote}/accept', [\App\Http\Controllers\QuoteController::class, 'accept'])->name('quotes.accept');
    Route::post('quotes/{quote}/convert', [\App\Http\Controllers\QuoteController::class, 'convertToInvoice'])->name('quotes.convert');

    // Invoices resource and extra actions
    Route::post('invoices/{invoice}/send', [\App\Http\Controllers\InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('invoices/{invoice}/payment', [\App\Http\Controllers\InvoiceController::class, 'addPayment'])->name('invoices.payment');
    Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
});

require __DIR__ . '/auth.php';
