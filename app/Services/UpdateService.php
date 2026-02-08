<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\License;
use Illuminate\Support\Facades\Log;

class UpdateService
{
    /**
     * Check for updates from Master Panel
     */
    public function check()
    {
        $license = License::first();
        $licenseKey = $license ? $license->license_key : null;
        $currentVersion = config('app.version', '1.0.0');

        $masterApiUrl = config('app.master_api_url', url('/api/master/check-update'));

        try {
            $response = Http::timeout(10)->post($masterApiUrl, [
                'license_code' => $licenseKey,
                'current_version' => $currentVersion,
                'domain' => request()->getHost(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Update check failed: ' . $response->status());
            return ['update_available' => false, 'message' => 'Sunucu hatası: ' . $response->status()];

        } catch (\Exception $e) {
            Log::error('Update check connection failed: ' . $e->getMessage());

            if (app()->isLocal()) {
                // Deadlock simulation bypass for single-threaded dev server
                return [
                    'update_available' => true,
                    'version' => '1.1.0-dev',
                    'release_notes_html' => '<p><strong>Geliştirici Modu:</strong> Bu bir simülasyon yanıtıdır. Yerel sunucuda (artisan serve) kendi kendine API isteği atıldığında deadlock oluştuğu için devreye girdi.</p><ul><li>Örnek özellik 1</li><li>Örnek özellik 2</li></ul>',
                    'download_url' => '#',
                    'is_critical' => false,
                    'published_at' => now(),
                ];
            }

            return ['update_available' => false, 'message' => 'Bağlantı hatası.'];
        }
    }
}
