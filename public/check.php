<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Self-Repair Diagnostic</title><style>
    body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f4; }
    .container { max-width: 1100px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .status { font-weight: bold; margin-bottom: 10px; }
    .ok { color: green; font-weight: bold; }
    .error { color: white; background: #d9534f; padding: 10px; border-radius: 4px; margin: 10px 0; }
    pre { background: #1e1e1e; color: #dcdcdc; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 11px; }
    .step { padding: 12px; margin-bottom: 10px; border: 1px solid #e1e1e1; border-radius: 6px; background: #fafafa; }
    .step-name { font-weight: bold; color: #555; display: inline-block; width: 250px; }
</style></head><body><div class='container'>";

echo "<h1>MIONEX Self-Repair Tool (v5)</h1>";

function run_step($name, $callback)
{
    global $app;
    echo "<div class='step'><span class='step-name'>$name:</span> ";
    try {
        $result = $callback();
        echo "<span class='ok'>✅ SUCCESS</span>";
        if ($result && $result !== true)
            echo "<pre>" . htmlspecialchars(print_r($result, true)) . "</pre>";
    } catch (Throwable $e) {
        echo "<span class='error'>❌ FAILED</span>";
        echo "<pre style='background: #400; color: white;'>";
        echo "MESSAGE: " . $e->getMessage() . "\n";
        echo "FILE:    " . $e->getFile() . "\n";
        echo "LINE:    " . $e->getLine() . "\n";
        echo "TRACE:\n" . $e->getTraceAsString();
        echo "</pre>";
    }
    echo "</div>";
}

run_step("1. Emergency Cache Wipe", function () {
    $files = glob('../bootstrap/cache/*.php');
    foreach ($files as $f)
        @unlink($f);
    return count($files) . " cache files nuked.";
});

run_step("2. Composer Autoload", function () {
    require '../vendor/autoload.php';
    return true;
});

run_step("3. Bootstrap App", function () {
    global $app;
    $app = require_once '../bootstrap/app.php';
    return "Application Instance Created";
});

run_step("4. Set Facade Root", function () {
    global $app;
    \Illuminate\Support\Facades\Facade::setFacadeApplication($app);
    return "Facade Root Application set manually.";
});

run_step("5. Check Critical Bindings", function () {
    global $app;
    $bindings = ['files', 'events', 'router', 'log', 'db', 'view', 'url', 'config'];
    $status = [];
    foreach ($bindings as $b) {
        $status[$b] = $app->bound($b) ? "BOUND" : "MISSING!!!";
    }
    return $status;
});

run_step("6. Full Application Boot", function () {
    global $app;
    // Manual registration of Filesystem if missing (sometimes happens in skeleton issues)
    if (!$app->bound('files')) {
        (new \Illuminate\Filesystem\FilesystemServiceProvider($app))->register();
    }
    $app->boot();
    return "Full boot successful!";
});

run_step("7. Loaded Service Providers", function () {
    global $app;
    $providers = array_keys($app->getLoadedProviders());
    sort($providers);
    return $providers;
});

echo "</div></body></html>";
