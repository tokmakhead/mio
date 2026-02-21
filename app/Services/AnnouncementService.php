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
        // 1. Check if we are in "Self-Master" mode or if loopback is problematic
        // If master_api_url is not set, or points to the same host, use direct DB query
        $masterApiUrl = config('app.master_api_url');
        $isLocal = !$masterApiUrl || str_contains(config('app.url'), parse_url($masterApiUrl, PHP_URL_HOST) ?? '');

        if ($isLocal && class_exists('\App\Models\MasterAnnouncement')) {
            try {
                return \App\Models\MasterAnnouncement::active()
                    ->where('target_type', 'all') // Standalone clients usually see "all"
                    ->orderByDesc('is_priority')
                    ->latest()
                    ->get()
                    ->toArray();
            } catch (\Exception $e) {
                Log::warning('Local announcement fetch failed: ' . $e->getMessage());
            }
        }

        // 2. Fallback to API call (for remote Master Panel architectures)
        $masterApiUrl = $masterApiUrl ?? url('/api/master/announcements');

        try {
            $response = Http::timeout(2)->get($masterApiUrl);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Announcement API fetch failed: ' . $response->status());
            return [];

        } catch (\Exception $e) {
            Log::error('Announcement connection failed: ' . $e->getMessage());
            return [];
        }
    }
}
