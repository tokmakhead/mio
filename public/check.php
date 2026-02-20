<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Diagnostic</title><style>
    body { font-family: sans-serif; line-height: 1.6; padding: 20px; background: #f4f4f4; }
    .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    .status { font-weight: bold; }
    .ok { color: green; }
    .error { color: red; }
    pre { background: #eee; padding: 10px; border-radius: 4px; overflow-x: auto; }
</style></head><body><div class='container'>";

echo "<h1>MIONEX Diagnostic Tool</h1>";

echo "<h3>Environment Info</h3>";
echo "<ul>";
echo "<li>PHP Version: " . PHP_VERSION . "</li>";
echo "<li>SAPI: " . php_sapi_name() . "</li>";
echo "<li>User: " . (function_exists('get_current_user') ? get_current_user() : 'Unknown') . "</li>";
echo "<li>OS: " . PHP_OS . "</li>";
echo "</ul>";

echo "<h3>Critical Extensions Check</h3>";
$extensions = ['pdo_sqlite', 'sqlite3', 'mbstring', 'openssl', 'xml', 'ctype', 'bcmath', 'json', 'tokenizer'];
echo "<ul>";
foreach ($extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "<li>$ext: " . ($loaded ? "<span class='ok'>✅ LOADED</span>" : "<span class='error'>❌ MISSING</span>") . "</li>";
}
echo "</ul>";

echo "<h3>Syntax Check (Manual Tokenization)</h3>";
$files = [
    '../app/Providers/AppServiceProvider.php',
    '../app/Observers/PaymentObserver.php',
    '../app/Observers/InvoiceObserver.php',
    '../routes/web.php',
    '../routes/auth.php',
    '../bootstrap/app.php',
    '../bootstrap/providers.php',
    '../config/app.php',
    '../config/database.php',
];

echo "<ul>";
foreach ($files as $file) {
    echo "<li><b>$file</b>: ";
    if (!file_exists($file)) {
        echo "<span class='error'>File not found</span>";
    } else {
        $content = file_get_contents($file);
        try {
            // Check for syntax errors by tokenizing
            // This is safer than including files that rely on the Laravel framework
            // If there's a serious parse error, this might still catch something or at least show we can read the file
            $tokens = token_get_all($content);
            echo "<span class='ok'>Tokenization Successful</span> (" . count($tokens) . " tokens)";
        } catch (Throwable $e) {
            echo "<span class='error'>SYNTAX ERROR: " . $e->getMessage() . "</span>";
        }
    }
    echo "</li>";
}
echo "</ul>";

echo "<h3>Maintenance Mode Check</h3>";
$maintenance = '../storage/framework/maintenance.php';
if (file_exists($maintenance)) {
    echo "<p class='error'>⚠️ Maintenance mode is ACTIVE! Delete $maintenance to restore.</p>";
} else {
    echo "<p class='ok'>✅ Maintenance mode is INACTIVE.</p>";
}

echo "<h3>Storage Permissions</h3>";
$paths = ['../storage', '../storage/logs', '../storage/framework', '../storage/framework/views', '../database'];
echo "<ul>";
foreach ($paths as $path) {
    $writable = is_writable($path);
    echo "<li>$path: " . ($writable ? "<span class='ok'>Writable</span>" : "<span class='error'>NOT Writable</span>") . "</li>";
}
echo "</ul>";

echo "</div></body></html>";
