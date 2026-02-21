<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterLicense;
use App\Models\MasterRelease;
use App\Models\MasterAnnouncement;
use App\Models\MasterActivityLog;
use Illuminate\Support\Str;

class MasterController extends Controller
{
    /**
     * Display the Master Dashboard.
     */
    public function index()
    {
        // Caching metrics for 10 minutes to improve performance
        $stats = \Illuminate\Support\Facades\Cache::remember('master_dashboard_stats', 600, function () {
            $totalLicenses = MasterLicense::count();
            $activeLicenses = MasterLicense::where('status', 'active')->count();
            $totalReleases = MasterRelease::count();
            $latestRelease = MasterRelease::latest('published_at')->first();

            // Financial Analytics
            $mrr = MasterLicense::where('status', 'active')
                ->where('billing_cycle', 'monthly')
                ->select('currency', \Illuminate\Support\Facades\DB::raw('SUM(price) as total'))
                ->groupBy('currency')
                ->get();

            $oneTimeRevenue = MasterLicense::where('billing_cycle', 'one-time')
                ->select('currency', \Illuminate\Support\Facades\DB::raw('SUM(price) as total'))
                ->groupBy('currency')
                ->get();

            // Churn Calculation (Expired / Total)
            $expiredCount = MasterLicense::all()->filter->isExpired()->count();
            $churnRate = $totalLicenses > 0 ? round(($expiredCount / $totalLicenses) * 100, 1) : 0;

            // Type Distribution
            $typeDist = MasterLicense::select('type', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get();

            // Version Distribution Analytics
            $versionDist = \App\Models\MasterLicenseInstance::select('version', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->groupBy('version')
                ->orderBy('count', 'desc')
                ->get();

            // System Health Data
            $systemHealth = [
                'memory_usage' => $this->getMemoryUsage(),
                'server_load' => $this->getServerLoad(),
                'disk_usage' => $this->getDiskUsage(),
            ];

            return array_merge(compact(
                'totalLicenses',
                'activeLicenses',
                'totalReleases',
                'latestRelease',
                'versionDist',
                'mrr',
                'oneTimeRevenue',
                'churnRate',
                'typeDist'
            ), ['systemHealth' => $systemHealth]);
        });

        return view('master.dashboard', $stats);
    }

    /**
     * License Management
     */
    public function licenses()
    {
        $licenses = MasterLicense::withCount('instances')->latest()->paginate(10);
        return view('master.licenses.index', compact('licenses'));
    }

    public function showLicenseInstances($id)
    {
        $license = MasterLicense::with('instances')->findOrFail($id);
        return view('master.licenses.instances', compact('license'));
    }

    public function createLicense()
    {
        return view('master.licenses.create');
    }

    public function storeLicense(Request $request)
    {
        $request->validate([
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'billing_cycle' => 'required|string|in:one-time,monthly,yearly',
            'activation_limit' => 'required|integer|min:1',
            'is_strict' => 'boolean',
            'features' => 'nullable|array',
            'expires_at' => 'nullable|date',
            'trial_ends_at' => 'nullable|date',
        ]);

        $features = [];
        if ($request->has('features')) {
            foreach ($request->features as $key => $value) {
                $features[$key] = (bool) $value;
            }
        }

        $license = MasterLicense::create([
            'code' => 'MIO-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'type' => $request->type,
            'price' => $request->price,
            'currency' => $request->currency,
            'billing_cycle' => $request->billing_cycle,
            'features' => $features,
            'status' => 'active',
            'is_strict' => $request->has('is_strict'),
            'activation_limit' => $request->activation_limit,
            'expires_at' => $request->expires_at,
            'trial_ends_at' => $request->trial_ends_at,
        ]);

        \Illuminate\Support\Facades\Cache::forget('master_dashboard_stats');

        MasterActivityLog::log('license_created', "Lisans oluşturuldu: {$license->code} ({$license->client_name})", $license);

        return redirect()->route('master.licenses.index')->with('success', 'Lisans başarıyla üretildi.');
    }

    /**
     * Release Management
     */
    public function releases()
    {
        $releases = MasterRelease::latest()->get();
        return view('master.releases.index', compact('releases'));
    }

    public function createRelease()
    {
        return view('master.releases.create');
    }

    public function storeRelease(Request $request)
    {
        $request->validate([
            'version' => 'required|string|unique:master_releases,version',
            'release_file' => 'required|file|mimes:zip|max:51200', // 50MB max
            'release_notes' => 'required|string',
        ]);

        if ($request->hasFile('release_file')) {
            $file = $request->file('release_file');
            $fileName = 'mio_v' . str_replace('.', '_', $request->version) . '_' . time() . '.zip';
            $path = $file->storeAs('releases', $fileName, 'public');

            $release = MasterRelease::create([
                'version' => $request->version,
                'release_notes' => $request->release_notes,
                'file_path' => $path,
                'is_critical' => $request->has('is_critical'),
                'requirements' => [
                    'php_version' => '8.1',
                    'laravel_version' => '10.x',
                ],
                'published_at' => now(),
            ]);

            \Illuminate\Support\Facades\Cache::forget('master_dashboard_stats');

            MasterActivityLog::log('release_published', "Yeni sürüm yayınlandı: {$release->version}", $release);

            return redirect()->route('master.releases.index')->with('success', 'Yeni sürüm başarıyla yüklendi ve yayınlandı.');
        }

        return back()->with('error', 'Dosya yüklenirken bir hata oluştu.');
    }

    public function showRelease($id)
    {
        $release = MasterRelease::findOrFail($id);
        return view('master.releases.show', compact('release'));
    }

    public function downloadRelease($id)
    {
        $release = MasterRelease::findOrFail($id);
        $release->increment('download_count');

        return response()->download(storage_path('app/public/' . $release->file_path));
    }

    /**
     * Announcement Management
     */
    public function announcements()
    {
        $announcements = MasterAnnouncement::latest()->get();
        return view('master.announcements.index', compact('announcements'));
    }

    public function createAnnouncement()
    {
        $licenses = MasterLicense::select('id', 'code', 'client_name')->get();
        return view('master.announcements.create', compact('licenses'));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|string',
            'target_type' => 'required|string|in:all,license',
            'master_license_id' => 'nullable|exists:master_licenses,id',
            'display_mode' => 'required|string|in:banner,modal,feed',
            'is_priority' => 'nullable|boolean',
        ]);

        $announcement = MasterAnnouncement::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'target_type' => $request->target_type,
            'master_license_id' => $request->master_license_id,
            'display_mode' => $request->display_mode,
            'is_active' => $request->has('is_active'),
            'is_dismissible' => $request->has('is_dismissible'),
            'is_priority' => $request->has('is_priority'),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        \Illuminate\Support\Facades\Cache::forget('master_dashboard_stats');

        MasterActivityLog::log('announcement_created', "Yeni duyuru yayınlandı: {$announcement->title}", $announcement);

        return redirect()->route('master.announcements.index')->with('success', 'Duyuru başarıyla oluşturuldu.');
    }

    public function destroyAnnouncement($id)
    {
        $announcement = MasterAnnouncement::findOrFail($id);
        MasterActivityLog::log('announcement_deleted', "Duyuru silindi: {$announcement->title}");
        $announcement->delete();
        return redirect()->route('master.announcements.index')->with('success', 'Duyuru silindi.');
    }

    public function cancelLicense($id)
    {
        $license = MasterLicense::findOrFail($id);
        $license->update(['status' => 'suspended']);
        MasterActivityLog::log('license_suspended', "Lisans askıya alındı: {$license->code}", $license);
        return back()->with('success', 'Lisans askıya alındı.');
    }

    public function logs()
    {
        $logs = \App\Models\MasterActivityLog::with('user', 'subject')->latest()->paginate(20);
        return view('master.logs.index', compact('logs'));
    }

    /**
     * Admin Management
     */
    public function admins()
    {
        $admins = \App\Models\User::where('is_master', true)->get();
        return view('master.admins.index', compact('admins'));
    }

    public function createAdmin()
    {
        return view('master.admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'master_role' => 'required|in:super_admin,manager,support',
        ]);

        $admin = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'is_master' => true,
            'master_role' => $request->master_role,
            'role' => 'admin', // General role fallback
        ]);

        \App\Models\MasterActivityLog::log('admin_created', "Yeni yönetici eklendi: {$admin->name} ({$admin->master_role})", $admin);

        return redirect()->route('master.admins.index')->with('success', 'Yönetici başarıyla eklendi.');
    }

    /**
     * Authentication
     */
    public function showLogin()
    {
        return view('master.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('master.dashboard');
        }

        return back()->withErrors([
            'email' => 'Giriş bilgileri hatalı.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('master.login');
    }

    private function getMemoryUsage()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return "N/A (Win)";
        }
        $free = shell_exec('free');
        if (!$free)
            return "N/A";
        $free = (string) trim($free);
        $free_arr = explode("\n", $free);
        if (!isset($free_arr[1]))
            return "N/A";
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        if (!isset($mem[2]) || !isset($mem[1]))
            return "N/A";
        $memory_usage = $mem[2] / $mem[1] * 100;
        return round($memory_usage, 1) . '%';
    }

    private function getServerLoad()
    {
        if (PHP_OS_FAMILY === 'Windows') {
            return "N/A (Win)";
        }
        $load = sys_getloadavg();
        return $load ? $load[0] . ' (1m)' : "N/A";
    }

    private function getDiskUsage()
    {
        $disktotal = disk_total_space('/');
        if (!$disktotal)
            return "N/A";
        $diskfree = disk_free_space('/');
        $diskused = $disktotal - $diskfree;
        $diskusage = ($diskused / $disktotal) * 100;
        return round($diskusage, 1) . '%';
    }
}