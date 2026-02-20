<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Environment Purifier</title><style>
    body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f4; }
    .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .ok { color: green; font-weight: bold; }
    .error { color: white; background: #d9534f; padding: 10px; border-radius: 4px; margin: 10px 0; }
    pre { background: #1e1e1e; color: #dcdcdc; padding: 15px; border-radius: 4px; overflow-x: auto; font-size: 11px; }
    .step { padding: 12px; margin-bottom: 15px; border: 1px solid #e1e1e1; border-radius: 6px; background: #fafafa; }
    .step-name { font-weight: bold; color: #555; display: inline-block; width: 300px; }
</style></head><body><div class='container'>";

echo "<h1>MIONEX Environment Purifier (v7)</h1>";

function run_repair($name, $callback)
{
    echo "<div class='step'><span class='step-name'>$name:</span> ";
    try {
        $result = $callback();
        echo "<span class='ok'>✅ FIXED/OK</span>";
        if ($result && $result !== true)
            echo "<pre>" . htmlspecialchars(print_r($result, true)) . "</pre>";
    } catch (Throwable $e) {
        echo "<span class='error'>❌ FAILED</span>";
        echo "<pre style='background: #400; color: white;'>" . $e->getMessage() . "</pre>";
    }
    echo "</div>";
}

run_repair("1. Brute Force Cache Purge", function () {
    $dir = '../bootstrap/cache';
    $files = glob($dir . '/*.php');
    $deleted = [];
    foreach ($files as $f) {
        // Try to overwrite first (in case of locking)
        @file_put_contents($f, '<?php return []; ?>');
        if (@unlink($f)) {
            $deleted[] = basename($f);
        } else {
            $deleted[] = basename($f) . " (OVERWRITTEN ONLY)";
        }
    }
    return $deleted;
});

run_repair("2. Permanent .env Fix (Linux Paths)", function () {
    $envPath = '../.env';
    if (!file_exists($envPath))
        return "No .env found";
    $content = file_get_contents($envPath);
    $linuxRoot = '/var/www/vhosts/mioly.app/mionex.mioly.app';

    $search = [
        'D:\\MIONEX\\database\\database.sqlite',
        'D:\\MIONEX',
        'APP_ENV=local',
        'APP_DEBUG=true',
        'APP_URL=http://localhost'
    ];
    $replace = [
        $linuxRoot . '/database/database.sqlite',
        $linuxRoot,
        'APP_ENV=production',
        'APP_DEBUG=false',
        'APP_URL=https://mionex.mioly.app'
    ];

    $newContent = str_replace($search, $replace, $content);
    file_put_contents($envPath, $newContent);
    return "Environment sanitized for Linux Production.";
});

run_repair("3. Storage Directory Cleanup", function () {
    $viewsDir = '../storage/framework/views';
    $files = glob($viewsDir . '/*');
    foreach ($files as $f)
        if (is_file($f) && basename($f) !== '.gitignore')
            @unlink($f);
    return "Storage views cleared.";
});

run_repair("4. App Bootstrap Test", function () {
    global $app;
    require '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $app->boot();
    return "System Booted Successfully (Cache-Free)!";
});

echo "</div>";
echo "<p><b>System is now clean.</b> <a href='/'>Click here to test Dashboard</a></p>";
echo "</body></html>";
