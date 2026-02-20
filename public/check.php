<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Deep Kernel Audit</title><style>
    body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f4; }
    .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .ok { color: green; font-weight: bold; }
    .error { color: white; background: #d9534f; padding: 10px; border-radius: 4px; margin: 10px 0; }
    pre { background: #1e1e1e; color: #dcdcdc; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 11px; }
    .step { padding: 12px; margin-bottom: 15px; border: 1px solid #e1e1e1; border-radius: 6px; background: #fafafa; }
    .step-name { font-weight: bold; color: #555; display: inline-block; width: 300px; }
</style></head><body><div class='container'>";

echo "<h1>MIONEX Deep Kernel Audit (v8)</h1>";

function run($name, $callback)
{
    echo "<div class='step'><span class='step-name'>$name:</span> ";
    try {
        $result = $callback();
        echo "<span class='ok'>✅ OK</span>";
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

run("1. Vendor Integrity (Size Check)", function () {
    $files = [
        '../vendor/autoload.php',
        '../vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
        '../vendor/laravel/framework/src/Illuminate/Filesystem/FilesystemServiceProvider.php',
        '../vendor/laravel/framework/src/Illuminate/Filesystem/Filesystem.php',
        '../vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php',
    ];
    $results = [];
    foreach ($files as $f) {
        $results[$f] = file_exists($f) ? filesize($f) . " bytes" : "MISSING!!!";
    }
    return $results;
});

run("2. Application.php Content Check", function () {
    $f = '../vendor/laravel/framework/src/Illuminate/Foundation/Application.php';
    if (!file_exists($f))
        return "N/A";
    $c = file_get_contents($f);
    return [
        'HasFilesystem' => (strpos($c, 'FilesystemServiceProvider') !== false ? 'YES' : 'NO'),
        'HasBaseRegistration' => (strpos($c, 'registerBaseServiceProviders') !== false ? 'YES' : 'NO'),
        'Version' => (preg_match('/const VERSION = \'(.*?)\';/', $c, $matches) ? $matches[1] : 'Unknown'),
    ];
});

run("3. Forced Boot with Injections", function () {
    require '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';

    $injected = [];
    if (!$app->bound('files')) {
        $app->singleton('files', function () {
            return new \Illuminate\Filesystem\Filesystem; });
        $injected[] = 'files';
    }

    // Attempt registration if still missing
    if (!$app->bound('db') && class_exists('\Illuminate\Database\DatabaseServiceProvider')) {
        (new \Illuminate\Database\DatabaseServiceProvider($app))->register();
        $injected[] = 'db';
    }

    $app->boot();
    return empty($injected) ? "Boot successful (No injections needed)" : "Boot successful after injecting: " . implode(', ', $injected);
});

run("4. Runtime Bound Services", function () {
    global $app;
    $services = ['files', 'events', 'router', 'log', 'db', 'view', 'config', 'translator', 'auth', 'session'];
    $status = [];
    foreach ($services as $s) {
        $status[$s] = $app->bound($s) ? "YES" : "NO";
    }
    return $status;
});

run("5. Route & Handler Test", function () {
    global $app;
    try {
        $request = \Illuminate\Http\Request::create('/ping', 'GET');
        $response = $app->handle($request);
        return "Route test (/ping) returned: " . $response->getStatusCode() . " - " . $response->getContent();
    } catch (Throwable $e) {
        return "Handler Error: " . $e->getMessage();
    }
});

echo "</div></body></html>";
