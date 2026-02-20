<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ------------------------------------------------------------
// MIONEX Real-World Boot Simulator (v9)
// Mimics exactly what public/index.php does
// ------------------------------------------------------------

echo "<html><head><title>MIONEX Boot Simulator v9</title><style>
    body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
    .box { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
    .ok { color: green; font-weight: bold; }
    .fail { color: red; font-weight: bold; }
    pre { background: #1e1e1e; color: #eee; padding: 12px; border-radius: 4px; font-size: 11px; overflow-x: auto; }
    .step { padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; }
</style></head><body><div class='box'>";

echo "<h1>MIONEX Boot Simulator (v9)</h1>";
echo "<p>This script mimics <code>public/index.php</code> step by step.</p>";

// STEP 1
echo "<div class='step'><b>Step 1: Cache Wipe</b> ";
$wiped = 0;
foreach (glob('../bootstrap/cache/*.php') as $f) {
    @unlink($f);
    $wiped++;
}
if (file_exists('../storage/framework/maintenance.php'))
    @unlink('../storage/framework/maintenance.php');
echo "<span class='ok'>✅ Wiped $wiped cache files.</span></div>";

// STEP 2
echo "<div class='step'><b>Step 2: Autoload</b> ";
try {
    require_once '../vendor/autoload.php';
    echo "<span class='ok'>✅ OK</span>";
} catch (Throwable $e) {
    echo "<span class='fail'>❌ " . $e->getMessage() . "</span>";
    die();
}
echo "</div>";

// STEP 3
echo "<div class='step'><b>Step 3: Bootstrap App (same as index.php)</b> ";
try {
    $app = require_once '../bootstrap/app.php';
    echo "<span class='ok'>✅ App created (Laravel " . $app::VERSION . ")</span>";
} catch (Throwable $e) {
    echo "<span class='fail'>❌ " . $e->getMessage() . "</span>";
    die();
}
echo "</div>";

// STEP 4: Simulate handleRequest()
echo "<div class='step'><b>Step 4: HTTP Kernel Bootstrap (what handleRequest() does)</b> ";
try {
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $request = \Illuminate\Http\Request::create('/', 'GET');

    // This is what handleRequest internally calls before handling
    $kernel->bootstrap();

    echo "<span class='ok'>✅ Kernel bootstrapped successfully!</span>";
} catch (Throwable $e) {
    echo "<span class='fail'>❌ FAILED HERE: " . $e->getMessage() . "</span>";
    echo "<pre>" . $e->getFile() . " line " . $e->getLine() . "\n" . $e->getTraceAsString() . "</pre>";
    echo "</div></div></body></html>";
    exit;
}
echo "</div>";

// STEP 5: Check bindings after proper bootstrap
echo "<div class='step'><b>Step 5: Services after proper bootstrap</b> ";
$services = ['files', 'events', 'router', 'log', 'db', 'view', 'config', 'session', 'auth'];
$status = [];
foreach ($services as $s) {
    $bound = $app->bound($s);
    $status[$s] = $bound ? "<span class='ok'>✅</span>" : "<span class='fail'>❌ MISSING</span>";
}
echo "<table>";
foreach ($status as $k => $v)
    echo "<tr><td><b>$k</b></td><td>$v</td></tr>";
echo "</table></div>";

// STEP 6: Handle test request
echo "<div class='step'><b>Step 6: Handle /ping Request</b> ";
try {
    $request = \Illuminate\Http\Request::create('/ping', 'GET');
    $response = $kernel->handle($request);
    echo "<span class='ok'>✅ Status: " . $response->getStatusCode() . "</span>";
    echo "<pre>" . htmlspecialchars($response->getContent()) . "</pre>";
} catch (Throwable $e) {
    echo "<span class='fail'>❌ " . $e->getMessage() . "</span>";
}
echo "</div>";

echo "<p>✅ Simulation complete. <a href='/'>Try Dashboard</a></p>";
echo "</div></body></html>";
