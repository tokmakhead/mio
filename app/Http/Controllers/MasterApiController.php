<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterLicense;
use App\Models\MasterRelease;
use App\Models\MasterAnnouncement;
use Carbon\Carbon;

class MasterApiController extends Controller
{
    /**
     * Verify License
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyLicense(Request $request)
    {
        // Simple Validation
        $request->validate([
            'license_code' => 'required|string',
            'domain' => 'nullable|string',
            'ip' => 'nullable|string',
        ]);

        $licenseCode = $request->license_code;
        $domain = $request->domain;
        $ip = $request->ip;

        // Find License
        $license = MasterLicense::where('code', $licenseCode)->first();

        // 1. License Not Found
        if (!$license) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid License Code',
                'code' => 'INVALID_LICENSE'
            ], 404);
        }

        // 2. License Suspended/Cancelled
        if ($license->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'License is ' . $license->status,
                'code' => 'LICENSE_' . strtoupper($license->status)
            ], 403);
        }

        // 3. License Expired
        if ($license->expires_at && Carbon::parse($license->expires_at)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'License Expired',
                'code' => 'LICENSE_EXPIRED'
            ], 403);
        }

        // 4. Domain Check (Optional binding)
        if ($license->domain && $domain && $license->domain !== $domain) {
            // Allow localhost for dev (or maybe not, strict mode?)
            if (!str_contains($domain, 'localhost') && !str_contains($domain, '127.0.0.1')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Domain Mismatch',
                    'code' => 'DOMAIN_MISMATCH'
                ], 403);
            }
        }

        // 5. IP Check (Optional binding)
        // Only bind if not set yet? Or strictly check?
        // For now, let's just log or update the last known IP/Domain if empty
        if (empty($license->domain) && $domain && !str_contains($domain, 'localhost')) {
            $license->domain = $domain;
            $license->save();
        }

        if (empty($license->ip_address) && $ip) {
            $license->ip_address = $ip;
            $license->save();
        }

        // Success Response
        return response()->json([
            'status' => 'success',
            'message' => 'License Verified',
            'data' => [
                'type' => $license->type,
                'client' => $license->client_name,
                'expires_at' => $license->expires_at,
            ]
        ]);
    }

    /**
     * Check for Updates
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUpdate(Request $request)
    {
        $currentVersion = $request->input('current_version', '1.0.0');

        // Logic: Find latest release where version > currentVersion
        // This requires version comparison logic.
        // For simplicity, we get the latest release and compare.

        $latestRelease = MasterRelease::latest('published_at')->first();

        if (!$latestRelease) {
            return response()->json([
                'update_available' => false,
                'message' => 'No releases found.'
            ]);
        }

        // Compare versions (simple string compare might fail for 1.10 vs 1.2, utilize version_comparephp)
        if (version_compare($latestRelease->version, $currentVersion, '>')) {
            return response()->json([
                'update_available' => true,
                'version' => $latestRelease->version,
                'release_notes_html' => $latestRelease->release_notes, // Send HTML notes
                'download_url' => url($latestRelease->file_path), // Full URL
                'is_critical' => (bool) $latestRelease->is_critical,
                'published_at' => $latestRelease->published_at,
            ]);
        }

        return response()->json([
            'update_available' => false,
            'current_version' => $currentVersion,
            'latest_version' => $latestRelease->version
        ]);
    }

    /**
     * Get Announcements
     */
    public function announcements()
    {
        $now = now();
        $announcements = MasterAnnouncement::where(function ($q) use ($now) {
            $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
        })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->latest()
            ->get();

        return response()->json($announcements);
    }
}
