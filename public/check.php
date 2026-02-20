<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Ultra Diagnostic</title><style>
    body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f4; }
    .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .status { font-weight: bold; margin-bottom: 10px; }
    .ok { color: green; font-weight: bold; }
    .error { color: white; background: #d9534f; padding: 10px; border-radius: 4px; margin: 10px 0; }
    pre { background: #1e1e1e; color: #dcdcdc; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 13px; border-left: 5px solid #5bc0de; }
    .step { padding: 12px; margin-bottom: 10px; border: 1px solid #e1e1e1; border-radius: 6px; background: #fafafa; }
    .step-name { font-weight: bold; color: #555; display: inline-block; width: 250px; }
</style></head><body><div class='container'>";

echo "<h1>MIONEX Ultra Diagnostic Tool</h1>";

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
        echo "<span class='error'>❌ FAILED (CRITICAL)</span>";
        echo "<pre style='background: #400; color: white;'>";
        echo "MESSAGE: " . $e->getMessage() . "\n";
        echo "FILE:    " . $e->getFile() . "\n";
        echo "LINE:    " . $e->getLine() . "\n\n";
        echo "TRACE:\n" . $e->getTraceAsString();
        echo "</pre>";
        // Don't stop the whole script if one step fails, but some steps are vital
    }
    echo "</div>";
}

run_step("1. Composer Autoload", function () {
    require '../vendor/autoload.php';
    return true;
});

run_step("2. Bootstrap App", function () {
    global $app;
    $app = require_once '../bootstrap/app.php';
    return "Application Instance Created";
});

run_step("3. Full Application Boot", function () {
    global $app;
    if (!$app)
        throw new Exception("App instance not available to boot.");

    // In Laravel 11, we boot the app by making it start handled
    $app->boot();
    return "All Service Providers Booted Successfully";
});

run_step("4. Route Resolution Test (/ping)", function () {
    global $app;
    if (!$app)
        throw new Exception("App instance not available.");

    $request = \Illuminate\Http\Request::create('/ping', 'GET');
    $response = $app->handle($request);

    return [
        'Status' => $response->getStatusCode(),
        'Content' => $response->getContent(),
        'Headers' => $response->headers->all()
    ];
});

run_step("5. Check .htaccess", function () {
    $file = './.htaccess';
    if (file_exists($file)) {
        return file_get_contents($file);
    }
    return "No .htaccess found in public/";
});

run_step("6. Database Query Test", function () {
    try {
        $users = \App\Models\User::count();
        return "User count: " . $users;
    } catch (Exception $e) {
        return "Query failed: " . $e->getMessage();
    }
});

echo "</div></body></html>";
