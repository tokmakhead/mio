<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Critical Diagnostic</title><style>
    body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f4; }
    .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .status { font-weight: bold; margin-bottom: 10px; }
    .ok { color: green; font-weight: bold; }
    .error { color: white; background: #d9534f; padding: 10px; border-radius: 4px; margin: 10px 0; }
    pre { background: #1e1e1e; color: #dcdcdc; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 13px; }
    .step { padding: 12px; margin-bottom: 10px; border: 1px solid #e1e1e1; border-radius: 6px; background: #fafafa; }
    .step-name { font-weight: bold; color: #555; display: inline-block; width: 250px; }
</style></head><body><div class='container'>";

echo "<h1>MIONEX Critical Diagnostic Tool</h1>";

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

run_step("1. Vendor File Existence", function () {
    $criticalFiles = [
        '../vendor/autoload.php',
        '../vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
        '../vendor/laravel/framework/src/Illuminate/Filesystem/FilesystemServiceProvider.php',
        '../vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php',
    ];
    $results = [];
    foreach ($criticalFiles as $f) {
        $results[$f] = file_exists($f) ? "EXISTS" : "MISSING!!!";
    }
    return $results;
});

run_step("2. Framework Version", function () {
    require '../vendor/autoload.php';
    return \Illuminate\Foundation\Application::VERSION;
});

run_step("3. Bootstrap App", function () {
    global $app;
    $app = require_once '../bootstrap/app.php';
    return "Application Instance Created";
});

run_step("4. Emergency Binding", function () {
    global $app;
    if (!$app->bound('files')) {
        $app->singleton('files', function () {
            return new \Illuminate\Filesystem\Filesystem;
        });
        return "MANUALLY BOUND 'files' to prevent EventServiceProvider crash";
    }
    return "Already bound";
});

run_step("5. Full Application Boot", function () {
    global $app;
    $app->boot();
    return "All Service Providers Booted Successfully";
});

run_step("6. Database Path Fix (Force)", function () {
    $envPath = '../.env';
    if (!file_exists($envPath))
        return "No .env";
    $content = file_get_contents($envPath);
    $linuxDbPath = '/var/www/vhosts/mioly.app/mionex.mioly.app/database/database.sqlite';
    if (strpos($content, 'D:\\MIONEX') !== false) {
        $content = str_replace('D:\\MIONEX\\database\\database.sqlite', $linuxDbPath, $content);
        file_put_contents($envPath, $content);
        return "Replaced Windows path with Linux path in .env";
    }
    return "Path looks okay or already fixed";
});

echo "</div></body></html>";
