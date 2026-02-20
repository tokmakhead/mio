<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Deep Diagnostic</title><style>
    body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f4; }
    .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .status { font-weight: bold; margin-bottom: 10px; }
    .ok { color: green; }
    .error { color: red; background: #fff0f0; padding: 5px; border-left: 3px solid red; }
    pre { background: #222; color: #0f0; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 13px; }
    .step { padding: 10px; margin-bottom: 5px; border: 1px solid #ddd; border-radius: 4px; }
    .step-name { font-weight: bold; }
</style></head><body><div class='container'>";

echo "<h1>MIONEX Deep Diagnostic Tool</h1>";

function run_step($name, $callback)
{
    echo "<div class='step'><span class='step-name'>$name:</span> ";
    try {
        $result = $callback();
        echo "<span class='ok'>✅ SUCCESS</span>";
        if ($result)
            echo "<pre>" . htmlspecialchars($result) . "</pre>";
    } catch (Throwable $e) {
        echo "<span class='error'>❌ FAILED</span>";
        echo "<pre>ERROR: " . $e->getMessage() . "\nFILE: " . $e->getFile() . "\nLINE: " . $e->getLine() . "\n\nTRACE:\n" . $e->getTraceAsString() . "</pre>";
    }
    echo "</div>";
}

run_step("Checking .env existence", function () {
    return file_exists('../.env') ? "Found" : throw new Exception(".env file missing!");
});

run_step("Checking Database file", function () {
    $db = '../database/database.sqlite';
    if (!file_exists($db))
        return "Missing (might be normal if not using sqlite)";
    return "Found: " . realpath($db) . " (" . filesize($db) . " bytes)";
});

run_step("Loading Composer Autoloader", function () {
    require '../vendor/autoload.php';
    return "Autoloader loaded";
});

run_step("Bootstrapping Application", function () {
    $app = require_once '../bootstrap/app.php';
    return "Application instance created";
});

run_step("Resolving Kernel", function () {
    global $app;
    if (!$app)
        return "App not globally available";
    // For Laravel 11+, we check handleRequest ability
    return method_exists($app, 'handleRequest') ? "Modern Application structure confirmed" : "Legacy or different structure";
});

run_step("Maintenance Check (Internal)", function () {
    $maintenance = '../storage/framework/maintenance.php';
    if (file_exists($maintenance)) {
        $data = json_decode(file_get_contents($maintenance), true);
        return "Maintenance is ACTIVE. Data: " . print_r($data, true);
    }
    return "Maintenance is INACTIVE.";
});

echo "<h3>PHP Info Snippets</h3>";
echo "<ul>";
echo "<li>Memory Limit: " . ini_get('memory_limit') . "</li>";
echo "<li>Max Execution Time: " . ini_get('max_execution_time') . "</li>";
echo "<li>Extensions: " . implode(', ', get_loaded_extensions()) . "</li>";
echo "</ul>";

echo "</div></body></html>";
