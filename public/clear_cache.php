<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>MIONEX Cache Cleaner</h1>";

$cachePath = __DIR__ . '/../bootstrap/cache';

if (is_dir($cachePath)) {
    $files = glob($cachePath . '/*.php');
    foreach ($files as $file) {
        if (basename($file) === 'packages.php' || basename($file) === 'services.php' || basename($file) === 'config.php' || basename($file) === 'routes-v7.php') {
            if (unlink($file)) {
                echo "<p style='color:green;'>✅ Deleted: " . basename($file) . "</p>";
            } else {
                echo "<p style='color:red;'>❌ Failed to delete: " . basename($file) . "</p>";
            }
        }
    }
    echo "<p>Cache directory sweep completed.</p>";
} else {
    echo "<p style='color:red;'>❌ Cache directory not found!</p>";
}

echo "<p><a href='/check.php'>Run Diagnostic Again</a> | <a href='/'>Go to Dashboard</a></p>";
