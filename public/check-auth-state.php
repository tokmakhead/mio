<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(Illuminate\Http\Request::capture());

use Illuminate\Support\Facades\DB;
use App\Models\BrandSetting;

echo "--- MIONEX DIAGNOSTICS ---\n";
echo "Locale: " . app()->getLocale() . "\n";
echo "APP_DEBUG: " . (config('app.debug') ? 'true' : 'false') . "\n";

try {
    $primaryColor = DB::table('brand_settings')->where('key', 'primary_color')->first();
    echo "Primary Color (DB): " . ($primaryColor ? $primaryColor->value : 'NOT SET') . "\n";

    $authFile = base_path('lang/tr/auth.php');
    echo "lang/tr/auth.php exists: " . (file_exists($authFile) ? 'YES' : 'NO') . "\n";
    if (file_exists($authFile)) {
        $trans = trans('auth.failed');
        echo "Translation for 'auth.failed': " . $trans . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
