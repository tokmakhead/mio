<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Manually set up debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $quoteId = 19; // The ID user mentioned
    $quote = \App\Models\Quote::find($quoteId);

    if (!$quote) {
        // Fallback to latest if 19 doesn't exist locally
        $quote = \App\Models\Quote::latest()->first();
    }

    if (!$quote) {
        die("No quotes found in database.");
    }

    echo "<h1>Debugging Quote: " . $quote->number . " (ID: " . $quote->id . ")</h1>";

    $service = new \App\Services\QuotePdfService();
    $data = $service->prepareData($quote);

    echo "<h2>Service Data Prepared Successfully</h2>";
    echo "<pre>";
    print_r(array_keys($data));
    echo "</pre>";

    echo "<h2>Rendering View...</h2>";

    // Render View
    $html = view('quotes.premium', $data)->render();

    echo "<h3>View Rendered Successfully!</h3>";
    echo "<hr>";
    echo $html;

} catch (\Throwable $e) {
    echo "<div style='background-color: #fee; padding: 20px; border: 1px solid red; color: red;'>";
    echo "<h1>Error Occurred</h1>";
    echo "<h3>" . $e->getMessage() . "</h3>";
    echo "<p><strong>File:</strong> " . $e->getFile() . ":" . $e->getLine() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
