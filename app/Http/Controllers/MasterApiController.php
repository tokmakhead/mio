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
        $version = $request->input('version');

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

        // 3. License Expired or Trial Ended
        if ($license->isExpired()) {
            return response()->json([
                'status' => 'error',
                'message' => 'License has expired or trial period has ended.',
                'code' => 'LICENSE_EXPIRED'
            ], 403);
        }

        // 4. Domain & IP Management (Advanced Logic)
        $instance = $license->instances()->where('domain', $domain)->first();

        if ($instance) {
            // Update existing instance
            $instance->update([
                'ip_address' => $ip,
                'version' => $version,
                'last_heard_at' => now(),
            ]);
        } else {
            // Check limits for new activation
            $currentCount = $license->instances()->count();
            if ($currentCount >= $license->activation_limit) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Activation Limit Reached (' . $license->activation_limit . ')',
                    'code' => 'LIMIT_REACHED'
                ], 403);
            }

            if ($license->is_strict && $license->domain && $license->domain !== $domain) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'License is strictly bound to another domain.',
                    'code' => 'STRICT_DOMAIN_MISMATCH'
                ], 403);
            }

            // Create new instance
            $license->instances()->create([
                'domain' => $domain,
                'ip_address' => $ip,
                'version' => $version,
                'last_heard_at' => now(),
            ]);
        }

        // Update last sync
        $license->update(['last_sync_at' => now()]);

        // Success Response
        return response()->json([
            'status' => 'success',
            'message' => 'License Verified',
            'data' => [
                'type' => $license->type,
                'client' => $license->client_name,
                'expires_at' => $license->expires_at,
                'is_trial' => $license->isTrial(),
                'features' => $license->features ?? [], // Entitlements
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
                'download_url' => route('master.releases.download', $latestRelease->id), // Secure tracked download
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
    public function announcements(Request $request)
    {
        // 1. Identify License (via Header or Parameter - Header is safer as it's signed)
        // In verifyLicense we log the instance. We can use the signature or a passed license key.
        $licenseKey = $request->header('X-Mio-License-Key');
        $license = null;

        if ($licenseKey) {
            $license = MasterLicense::where('code', $licenseKey)->first();
        }

        // 2. Fetch active announcements
        $query = MasterAnnouncement::active();

        // 3. Apply targeting logic
        $query->where(function ($q) use ($license) {
            $q->where('target_type', 'all');

            if ($license) {
                $q->orWhere(function ($sq) use ($license) {
                    $sq->where('target_type', 'license')
                        ->where('master_license_id', $license->id);
                });
            }
        });

        $announcements = $query->orderByDesc('is_priority')->latest()->get();

        return response()->json($announcements);
    }
}
