<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnnouncementService
{
    /**
     * Fetch active announcements from Master Panel
     */
    public function fetch()
    {
        $masterApiUrl = config('app.master_api_url', url('/api/master/announcements'));

        try {
            $response = Http::timeout(2)->get($masterApiUrl);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Announcement fetch failed: ' . $response->status());
            return [];

        } catch (\Exception $e) {
            // Deadlock situation (local dev) or simple connection error
            if (app()->isLocal()) {
                // Return empty instead of mock data
                return [];
            }

            Log::error('Announcement connection failed: ' . $e->getMessage());
            return [];
        }
    }
}
