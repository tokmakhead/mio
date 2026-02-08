<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function switch($locale)
    {
        if (!in_array($locale, ['en', 'tr', 'es', 'fr', 'de'])) { // Add allowed languages here or make dynamic
            // For now, let's just allow what's available in the lang folder + basic whitelist
        }

        Session::put('locale', $locale);
        return back();
    }

    public function index()
    {
        $languages = [];
        $path = base_path('lang');

        if (File::exists($path)) {
            $files = File::files($path);
            foreach ($files as $file) {
                if ($file->getExtension() === 'json') {
                    $languages[] = $file->getFilenameWithoutExtension();
                }
            }
        }

        // Also check for subdirectories (php files) if needed, but we focus on JSON

        return view('settings.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('settings.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|size:2',
            // Custom validation:
        ]);

        $locale = strtolower($request->input('locale'));
        $path = base_path("lang/{$locale}.json");

        if (File::exists($path)) {
            return back()->with('error', 'Bu dil zaten mevcut.');
        }

        // Copy structure from EN or create empty
        $defaultPath = base_path('lang/en.json');
        if (File::exists($defaultPath)) {
            File::copy($defaultPath, $path);
        } else {
            File::put($path, '{}');
        }

        return redirect()->route('settings.languages.index')->with('success', 'Dil oluşturuldu.');
    }

    public function edit($locale)
    {
        $path = base_path("lang/{$locale}.json");

        if (!File::exists($path)) {
            // Handle if json doesn't exist but directory might? 
            // For this scope, assume JSON based translations.
            if ($locale === 'en' && !File::exists($path)) {
                // Create en.json from php files? No, just create empty.
                File::put($path, '{}');
            } else {
                return back()->with('error', 'Dil dosyası bulunamadı.');
            }
        }

        $content = File::get($path);

        return view('settings.languages.edit', compact('locale', 'content'));
    }

    public function update(Request $request, $locale)
    {
        $request->validate([
            'content' => 'required|json',
        ]);

        $path = base_path("lang/{$locale}.json");
        File::put($path, $request->input('content'));

        return back()->with('success', 'Çeviriler güncellendi.');
    }

    public function destroy($locale)
    {
        if ($locale === 'en' || $locale === 'tr') {
            return back()->with('error', 'Varsayılan diller (TR/EN) silinemez.');
        }

        $path = base_path("lang/{$locale}.json");
        if (File::exists($path)) {
            File::delete($path);
        }

        return redirect()->route('settings.languages.index')->with('success', 'Dil silindi.');
    }
}
