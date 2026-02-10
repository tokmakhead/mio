<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\LanguageController;
use Illuminate\Support\Facades\Route;

// Installation Routes
Route::group(['prefix' => 'install', 'as' => 'install.', 'middleware' => ['web']], function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('requirements');
    Route::get('/permissions', [InstallController::class, 'permissions'])->name('permissions');
    Route::get('/database', [InstallController::class, 'database'])->name('database');
    Route::post('/database', [InstallController::class, 'saveDatabase'])->name('database.save');
    Route::get('/license', [InstallController::class, 'license'])->name('license');
    Route::post('/license', [InstallController::class, 'verifyLicense'])->name('license.verify');
    Route::get('/admin', [InstallController::class, 'admin'])->name('admin');
    Route::post('/admin', [InstallController::class, 'saveAdmin'])->name('admin.save');
    Route::get('/finish', [InstallController::class, 'finish'])->name('finish');
});

Route::middleware('guest')->group(function () {
    // Other guest routes if any...
});

Route::get('license', [App\Http\Controllers\LicenseController::class, 'show'])->name('license.show');
Route::post('license', [App\Http\Controllers\LicenseController::class, 'activate'])->name('license.activate.action');

Route::get('/', function () {
    if (!app()->environment('local') && !file_exists(storage_path('installed'))) {
        return redirect()->route('install.welcome');
    }
    return view('mionex_landing');
});

Route::get('/hub', function () {
    return view('mioly_hub');
})->name('hub');

Route::get('/demo', function () {
    $user = \App\Models\User::where('email', 'admin@mioly.app')->first();
    if ($user) {
        Auth::login($user);
        return redirect()->route('dashboard')->with('success', 'Demo sistemine hoş geldiniz!');
    }
    return redirect()->route('login')->with('error', 'Demo kullanıcısı bulunamadı.');
})->name('demo');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Payments & Ledger
    Route::get('payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::get('invoices/{invoice}/payments/create', [\App\Http\Controllers\PaymentController::class, 'create'])->name('invoices.payments.create');
    Route::post('invoices/{invoice}/payments', [\App\Http\Controllers\PaymentController::class, 'store'])->name('invoices.payments.store');
    Route::get('customers/{customer}/ledger', [\App\Http\Controllers\CustomerLedgerController::class, 'show'])->name('customers.ledger');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Accounting Reconciliation (Integrated into Invoices)
    Route::get('/invoices/reconciliation', [\App\Http\Controllers\ReconciliationController::class, 'index'])->name('accounting.reconciliation.index');
    Route::post('/invoices/reconciliation/fix', [\App\Http\Controllers\ReconciliationController::class, 'fix'])->name('accounting.reconciliation.fix');

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

    // Revenue Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ReportController::class, 'index'])->name('index');
        Route::get('/revenue', [\App\Http\Controllers\ReportController::class, 'revenue'])->name('revenue');
        Route::get('/revenue/csv', [\App\Http\Controllers\ReportController::class, 'revenueCsv'])->name('revenue.csv');
        Route::get('/revenue/pdf', [\App\Http\Controllers\ReportController::class, 'revenuePdf'])->name('revenue.pdf');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        // Brand
        Route::get('/brand', [SettingsController::class, 'brand'])->name('brand');
        Route::post('/brand', [SettingsController::class, 'updateBrand'])->name('brand.update');

        // Language Settings
        Route::get('/languages', [LanguageController::class, 'index'])->name('languages.index');
        Route::get('/languages/create', [LanguageController::class, 'create'])->name('languages.create');
        Route::post('/languages', [LanguageController::class, 'store'])->name('languages.store');
        Route::get('/languages/{locale}/edit', [LanguageController::class, 'edit'])->name('languages.edit');
        Route::put('/languages/{locale}', [LanguageController::class, 'update'])->name('languages.update');
        Route::delete('/languages/{locale}', [LanguageController::class, 'destroy'])->name('languages.destroy');

        // Payment Gateways
        Route::get('/gateways', [SettingsController::class, 'gateways'])->name('gateways');
        Route::put('/gateways', [SettingsController::class, 'updateGateways'])->name('gateways.update');

        // SMTP
        Route::get('/smtp', [SettingsController::class, 'smtp'])->name('smtp');
        Route::post('/smtp', [SettingsController::class, 'updateSmtp'])->name('smtp.update');
        Route::post('/smtp/test', [SettingsController::class, 'testSmtp'])->name('smtp.test');

        // Email Templates
        Route::get('/email-templates', [\App\Http\Controllers\SettingsController::class, 'emailTemplates'])->name('templates');
        Route::post('/email-templates/{template}', [\App\Http\Controllers\SettingsController::class, 'updateEmailTemplate'])->name('templates.update');
        Route::post('/email-templates/{template}/test', [\App\Http\Controllers\SettingsController::class, 'testEmailTemplate'])->name('templates.test');

        // Site
        Route::get('/site', [\App\Http\Controllers\SettingsController::class, 'site'])->name('site');
        Route::post('/site', [\App\Http\Controllers\SettingsController::class, 'updateSite'])->name('site.update');

        // Financial
        Route::get('/financial', [\App\Http\Controllers\SettingsController::class, 'financial'])->name('financial');
        Route::post('/financial', [\App\Http\Controllers\SettingsController::class, 'updateFinancial'])->name('financial.update');

        // System Settings
        Route::get('/system', [SettingsController::class, 'system'])->name('system');
        Route::post('/system', [SettingsController::class, 'updateSystem'])->name('system.update');
        Route::post('/check-update', [SettingsController::class, 'checkUpdate'])->name('check-update');
        Route::post('/cache/clear', [SettingsController::class, 'clearCache'])->name('cache.clear');
        Route::post('/logs/clear', [SettingsController::class, 'clearLogs'])->name('logs.clear');
    });
});

// Language Switcher
Route::get('lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

require __DIR__ . '/auth.php';

// MASTER MANAGEMENT PANEL (Simulator)
// Public Master Routes (Login)
Route::group(['prefix' => 'master', 'as' => 'master.', 'middleware' => ['web']], function () {
    Route::get('/login', [\App\Http\Controllers\MasterController::class, 'showLogin'])->name('login');
    Route::post('/login', [\App\Http\Controllers\MasterController::class, 'authenticate'])->name('login.action');
    Route::post('/logout', [\App\Http\Controllers\MasterController::class, 'logout'])->name('logout');
});

// Protected Master Routes
Route::group(['prefix' => 'master', 'as' => 'master.', 'middleware' => ['web', 'auth']], function () {
    Route::get('/', [App\Http\Controllers\MasterController::class, 'index'])->name('dashboard');

    // Licenses
    Route::get('/licenses', [App\Http\Controllers\MasterController::class, 'licenses'])->name('licenses.index');
    Route::get('/licenses/create', [App\Http\Controllers\MasterController::class, 'createLicense'])->name('licenses.create');
    Route::post('/licenses', [App\Http\Controllers\MasterController::class, 'storeLicense'])->name('licenses.store');
    Route::post('/licenses/{id}/cancel', [App\Http\Controllers\MasterController::class, 'cancelLicense'])->name('licenses.cancel');

    // Releases
    Route::get('/releases', [App\Http\Controllers\MasterController::class, 'releases'])->name('releases.index');
    Route::get('/releases/create', [App\Http\Controllers\MasterController::class, 'createRelease'])->name('releases.create');
    Route::post('/releases', [App\Http\Controllers\MasterController::class, 'storeRelease'])->name('releases.store');
    Route::get('/releases/{id}', [App\Http\Controllers\MasterController::class, 'showRelease'])->name('releases.show');

    // Announcements
    Route::get('/announcements', [App\Http\Controllers\MasterController::class, 'announcements'])->name('announcements.index');
    Route::get('/announcements/create', [App\Http\Controllers\MasterController::class, 'createAnnouncement'])->name('announcements.create');
    Route::post('/announcements', [App\Http\Controllers\MasterController::class, 'storeAnnouncement'])->name('announcements.store');
    Route::delete('/announcements/{id}', [App\Http\Controllers\MasterController::class, 'destroyAnnouncement'])->name('announcements.destroy');
});
