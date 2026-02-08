<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LicenseController extends Controller
{
    public function show()
    {
        // If already licensed, redirect to dashboard
        $license = License::first();
        if ($license && $license->isActive()) {
            return redirect()->route('dashboard');
        }

        return view('license.activate');
    }

    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string|min:10',
            'client_name' => 'required|string|max:255',
        ]);

        $key = $request->input('license_key');
        $client = $request->input('client_name');

        // MOCK VERIFICATION LOGIC
        // In a real scenario, we would make an HTTP request to a licensing server here.
        // For this productization, we simulate a successful response for specific keys or formats.

        $isValid = $this->mockVerify($key);

        if (!$isValid) {
            return back()->withErrors(['license_key' => 'Geçersiz lisans anahtarı. Lütfen tekrar deneyin.']);
        }

        // Store License
        License::truncate(); // Ensure only one license exists
        License::create([
            'license_key' => $key,
            'client_name' => $client,
            'domain' => $request->getHost(),
            'status' => 'active',
            'last_check_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Lisans başarıyla etkinleştirildi! Hoş geldiniz.');
    }

    private function mockVerify($key)
    {
        // Simple mock validation: check if key starts with "MIO-"
        return str_starts_with($key, 'MIO-');
    }
}
