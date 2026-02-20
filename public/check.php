<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<html><head><title>MIONEX Maintenance Killer</title><style>
    body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
    .box { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
    .ok { color: green; font-weight: bold; }
    .fail { color: red; font-weight: bold; }
    .warn { color: orange; font-weight: bold; }
    pre { background: #1e1e1e; color: #eee; padding: 12px; border-radius: 4px; font-size: 11px; overflow-x: auto; }
    .step { padding: 10px; margin: 10px 0; border: 1px solid #ddd; border-radius: 6px; }
    h1 { color: #d9534f; }
</style></head><body><div class='box'>";

echo "<h1>🔪 MIONEX Maintenance Killer (v10)</h1>";

// ─────────────────────────────────────────────────────────
// STEP 1: Scan ALL storage/framework files
// ─────────────────────────────────────────────────────────
echo "<div class='step'><b>Step 1: Scan storage/framework (all files)</b><br/>";
$storagePath = '../storage/framework';
$allFiles = [];
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storagePath, FilesystemIterator::SKIP_DOTS)) as $file) {
    if ($file->isFile() && $file->getFilename() !== '.gitignore') {
        $path = $file->getPathname();
        $allFiles[] = [
            'path' => $path,
            'size' => $file->getSize(),
            'perms' => substr(sprintf('%o', fileperms($path)), -4),
            'writable' => is_writable($path) ? 'YES' : 'NO',
        ];
    }
}
if (empty($allFiles)) {
    echo "<span class='ok'>✅ storage/framework is clean (no leftover files)</span>";
} else {
    echo "<pre>";
    foreach ($allFiles as $f) {
        echo $f['path'] . " [" . $f['size'] . " bytes, perms:" . $f['perms'] . ", writable:" . $f['writable'] . "]\n";
    }
    echo "</pre>";
}
echo "</div>";

// ─────────────────────────────────────────────────────────
// STEP 2: Nuclear wipe of ALL maintenance-related files
// ─────────────────────────────────────────────────────────
echo "<div class='step'><b>Step 2: Nuclear Maintenance Wipe</b><br/>";
$patterns = [
    '../storage/framework/maintenance.php',
    '../storage/framework/maintenance.json',
    '../storage/framework/down',
    '../storage/framework/down.php',
    '../storage/framework/down.json',
];
$deleted = [];
$failed = [];
foreach ($patterns as $p) {
    if (file_exists($p)) {
        if (@unlink($p)) {
            $deleted[] = $p;
        } else {
            // Try to overwrite so it returns empty/no-op
            if (@file_put_contents($p, '<?php // cleared')) {
                $failed[] = $p . " (overwritten)";
            } else {
                $failed[] = $p . " (CANNOT delete or overwrite - permission denied!)";
            }
        }
    }
}
if (empty($deleted) && empty($failed)) {
    echo "<span class='ok'>✅ No maintenance files found.</span>";
} else {
    if (!empty($deleted))
        echo "<p class='ok'>✅ Deleted: " . implode(', ', $deleted) . "</p>";
    if (!empty($failed))
        echo "<p class='warn'>⚠️ " . implode('<br/>', $failed) . "</p>";
}
echo "</div>";

// ─────────────────────────────────────────────────────────
// STEP 3: Bootstrap app + call artisan up
// ─────────────────────────────────────────────────────────
echo "<div class='step'><b>Step 3: Full Boot + Artisan 'up'</b><br/>";
try {
    require_once '../vendor/autoload.php';
    $app = require_once '../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();

    // Bring app out of maintenance
    $exitCode = \Illuminate\Support\Facades\Artisan::call('up');
    echo "<span class='ok'>✅ Artisan 'up' executed. Exit code: $exitCode</span>";
    echo "<pre>" . htmlspecialchars(\Illuminate\Support\Facades\Artisan::output()) . "</pre>";
} catch (Throwable $e) {
    echo "<span class='fail'>❌ " . $e->getMessage() . "</span>";
    echo "<pre>" . $e->getFile() . ":" . $e->getLine() . "</pre>";
}
echo "</div>";

// ─────────────────────────────────────────────────────────
// STEP 4: Test ping route AFTER maintenance removal
// ─────────────────────────────────────────────────────────
echo "<div class='step'><b>Step 4: Test / route after fix</b><br/>";
try {
    // Need a fresh app instance since the previous one may be in a weird state
    $request = \Illuminate\Http\Request::create('/', 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    if ($status === 200) {
        echo "<span class='ok'>✅ Status: 200 - SITE IS LIVE!</span>";
    } elseif ($status === 302) {
        echo "<span class='ok'>✅ Status: 302 - Redirecting (probably to login) - SITE IS LIVE!</span>";
        echo "<pre>Location: " . $response->headers->get('Location') . "</pre>";
    } elseif ($status === 503) {
        echo "<span class='fail'>❌ Status: 503 - Still in maintenance. Something is REGENERATING the maintenance file.</span>";
    } else {
        echo "<span class='warn'>⚠️ Status: $status</span>";
        echo "<pre>" . htmlspecialchars(substr($response->getContent(), 0, 500)) . "</pre>";
    }
} catch (Throwable $e) {
    echo "<span class='fail'>❌ " . $e->getMessage() . "</span>";
}
echo "</div>";

echo "<p>Done. <a href='/'>Go to Dashboard</a></p>";
echo "</div></body></html>";
