<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterLicense;
use App\Models\MasterRelease;
use App\Models\MasterAnnouncement;
use Illuminate\Support\Str;

class MasterController extends Controller
{
    /**
     * Display the Master Dashboard.
     */
    public function index()
    {
        $totalLicenses = MasterLicense::count();
        $activeLicenses = MasterLicense::where('status', 'active')->count();
        $totalReleases = MasterRelease::count();
        $latestRelease = MasterRelease::latest('published_at')->first();

        return view('master.dashboard', compact('totalLicenses', 'activeLicenses', 'totalReleases', 'latestRelease'));
    }

    /**
     * License Management
     */
    public function licenses()
    {
        $licenses = MasterLicense::latest()->paginate(10);
        return view('master.licenses.index', compact('licenses'));
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
        ]);

        MasterLicense::create([
            'code' => 'MIO-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'type' => $request->type,
            'status' => 'active',
            'expires_at' => $request->expires_at, // Optional
        ]);

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
            'version' => 'required|string',
            'file_path' => 'required|string', // In real app, file upload
        ]);

        MasterRelease::create([
            'version' => $request->version,
            'release_notes' => $request->release_notes,
            'file_path' => $request->file_path,
            'is_critical' => $request->has('is_critical'),
            'published_at' => now(),
        ]);

        return redirect()->route('master.releases.index')->with('success', 'Yeni sürüm yayınlandı.');
    }

    public function showRelease($id)
    {
        $release = MasterRelease::findOrFail($id);
        return view('master.releases.show', compact('release'));
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
        return view('master.announcements.create');
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,danger,success',
        ]);

        MasterAnnouncement::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('master.announcements.index')->with('success', 'Duyuru başarıyla yayınlandı.');
    }

    public function destroyAnnouncement($id)
    {
        MasterAnnouncement::findOrFail($id)->delete();
        return redirect()->route('master.announcements.index')->with('success', 'Duyuru silindi.');
    }

    public function cancelLicense($id)
    {
        $license = MasterLicense::findOrFail($id);
        $license->status = 'cancelled';
        $license->save();

        return back()->with('success', 'Lisans iptal edildi. Kullanıcı sisteme erişemeyecek.');
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
            return redirect()->intended(route('master.dashboard'));
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
}
