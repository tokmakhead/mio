<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request = Illuminate\Http\Request::capture());

echo "<h1>System Force Update</h1>";

echo "<li>Clearing View Cache... ";
\Illuminate\Support\Facades\Artisan::call('view:clear');
echo "DONE</li>";

echo "<li>Clearing Route Cache... ";
\Illuminate\Support\Facades\Artisan::call('route:clear');
echo "DONE</li>";

echo "<li>Clearing Config Cache... ";
\Illuminate\Support\Facades\Artisan::call('config:clear');
echo "DONE</li>";

echo "<li>Clearing Fast-Route... ";
\Illuminate\Support\Facades\Artisan::call('optimize:clear');
echo "DONE</li>";

if (function_exists('opcache_reset')) {
    echo "<li>Resetting OPcache... ";
    opcache_reset();
    echo "DONE</li>";
} else {
    echo "<li>OPcache is not enabled or accessible.</li>";
}

echo "<br><br><strong style='color: green;'>All caches cleared! Please try the PDF again.</strong>";
echo "<br><a href='/invoices'>Back to Dashboard</a>";
