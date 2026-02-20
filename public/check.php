<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Ultra Deep Diagnostic</title><style>
    body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f4; }
    .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .ok { color: green; font-weight: bold; }
    .error { color: white; background: #d9534f; padding: 10px; border-radius: 4px; margin: 10px 0; }
    pre { background: #1e1e1e; color: #dcdcdc; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 11px; }
    .step { padding: 12px; margin-bottom: 15px; border: 1px solid #e1e1e1; border-radius: 6px; background: #fafafa; }
    .step-name { font-weight: bold; color: #555; display: inline-block; width: 300px; }
</style></head><body><div class='container'>";

echo "<h1>MIONEX Ultra Deep Diagnostic (v6)</h1>";

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
        echo "</pre>";
    }
    echo "</div>";
}

run_step("1. Framework & PHP Audit", function () {
    return [
        'PHP_VERSION' => PHP_VERSION,
        'LARAVEL_VERSION' => defined('\Illuminate\Foundation\Application::VERSION') ? \Illuminate\Foundation\Application::VERSION : 'Unknown',
        'SERVER_ADDR' => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
        'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
    ];
});

run_step("2. Vendor Integrity (Deep Scan)", function () {
    $paths = [
        '../vendor/laravel/framework/src/Illuminate/Foundation/Configuration/ApplicationBuilder.php',
        '../vendor/laravel/framework/src/Illuminate/Database/DatabaseServiceProvider.php',
        '../vendor/laravel/framework/src/Illuminate/View/ViewServiceProvider.php',
        '../vendor/laravel/framework/src/Illuminate/Session/SessionServiceProvider.php',
    ];
    $results = [];
    foreach ($paths as $p) {
        $results[$p] = file_exists($p) ? "FOUND" : "NOT FOUND (CRITICAL!!!)";
    }
    return $results;
});

run_step("3. Load & Bootstrapping", function () {
    global $app;
    require '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    return "App Instance created via bootstrap/app.php";
});

run_step("4. Emergency Core Injection", function () {
    global $app;
    $coreProviders = [
        'files' => \Illuminate\Filesystem\FilesystemServiceProvider::class,
        'config' => \Illuminate\Foundation\Providers\FormRequestServiceProvider::class, // Just a placeholder to see
        'db' => \Illuminate\Database\DatabaseServiceProvider::class,
        'view' => \Illuminate\View\ViewServiceProvider::class,
    ];

    $injected = [];
    foreach ($coreProviders as $key => $class) {
        if (!$app->bound($key)) {
            if (class_exists($class)) {
                $p = new $class($app);
                if (method_exists($p, 'register'))
                    $p->register();
                $injected[] = $class;
            } else {
                $injected[] = "FAILED TO FIND CLASS: $class";
            }
        }
    }
    return empty($injected) ? "Nothing missing" : $injected;
});

run_step("5. Full Application Boot (Attempt)", function () {
    global $app;
    $app->boot();
    return "All Service Providers Booted Successfully";
});

run_step("6. Database & Model Test (SQLite)", function () {
    global $app;
    // Force set config if missing
    if (!$app->bound('config')) {
        echo "Config missing, skip DB test.";
        return false;
    }
    try {
        $count = \App\Models\User::count();
        return "Database Connection OK. Total Users: $count";
    } catch (Throwable $e) {
        return "DB FAILED: " . $e->getMessage();
    }
});

run_step("7. Why were providers missing? (Hypothesis Check)", function () {
    global $app;
    $providersFile = '../bootstrap/cache/services.php';
    if (file_exists($providersFile)) {
        return "Found STALE services.php cache! This might be the cause if my Wipe failed.";
    }
    return "No cache file found. Discovery should be automatic.";
});

echo "</div></body></html>";
