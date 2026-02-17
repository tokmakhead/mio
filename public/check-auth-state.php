<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;

echo "--- MIONEX EMERGENCY FIX ---\n";

try {
    // 1. Force fix the primary color in DB to MIONEX Red
    DB::table('brand_settings')->updateOrInsert(
        ['key' => 'primary_color'],
        ['value' => '#de4968']
    );
    echo "Primary Color (DB) updated to: #de4968 (MIONEX Red)\n";

    // 2. Clear Caches
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "Laravel Caches Cleared.\n";

    // 3. Status Report
    echo "Locale: " . app()->getLocale() . "\n";
    $primaryColor = DB::table('brand_settings')->where('key', 'primary_color')->first();
    echo "Current Color in DB: " . ($primaryColor ? $primaryColor->value : 'NOT SET') . "\n";

    $authFile = base_path('lang/tr/auth.php');
    echo "lang/tr/auth.php exists: " . (file_exists($authFile) ? 'YES' : 'NO') . "\n";
    if (file_exists($authFile)) {
        $trans = trans('auth.failed');
        echo "Translation for 'auth.failed': " . $trans . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
