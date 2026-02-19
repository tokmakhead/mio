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
        // Security Fix: Check if language file exists before switching
        if (!File::exists(base_path("lang/{$locale}.json")) && !File::exists(base_path("lang/{$locale}"))) {
            return back()->with('error', 'Geçersiz dil seçimi.');
        }

        Session::put('locale', $locale);
        return back();
    }

    public function index()
    {
        $languages = [];
        $files = [];
        $path = base_path('lang');

        if (File::exists($path)) {
            // Get JSON files (Languages)
            $jsonFiles = File::files($path);
            foreach ($jsonFiles as $file) {
                if ($file->getExtension() === 'json') {
                    $languages[] = $file->getFilenameWithoutExtension();
                }
            }

            // Get PHP files for each language (to show in a nested or separate view)
            // For simplicity, we'll list PHP files available in 'en' directory as templates
            $phpPath = base_path('lang/en');
            if (File::exists($phpPath)) {
                $phpFiles = File::files($phpPath);
                foreach ($phpFiles as $file) {
                    if ($file->getExtension() === 'php') {
                        $files[] = $file->getFilename();
                    }
                }
            }
        }

        return view('settings.languages.index', compact('languages', 'files'));
    }

    public function create()
    {
        return view('settings.languages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|size:2',
        ]);

        $locale = strtolower($request->input('locale'));
        $jsonPath = base_path("lang/{$locale}.json");
        $phpDir = base_path("lang/{$locale}");

        if (File::exists($jsonPath)) {
            return back()->with('error', 'Bu dil zaten mevcut.');
        }

        // 1. Create JSON file
        $defaultJsonPath = base_path('lang/en.json');
        if (File::exists($defaultJsonPath)) {
            File::copy($defaultJsonPath, $jsonPath);
        } else {
            File::put($jsonPath, '{}');
        }

        // 2. Create Directory and PHP files
        if (!File::exists($phpDir)) {
            File::makeDirectory($phpDir);
            $defaultPhpDir = base_path('lang/en');
            if (File::exists($defaultPhpDir)) {
                File::copyDirectory($defaultPhpDir, $phpDir);
            }
        }

        return redirect()->route('settings.languages.index')->with('success', 'Dil oluşturuldu.');
    }

    public function edit($locale, Request $request)
    {
        $file = $request->query('file', 'json'); // 'json' or 'auth.php', etc.

        // 1. Load Current Locale Translations
        if ($file === 'json') {
            $path = base_path("lang/{$locale}.json");
            $translations = File::exists($path) ? json_decode(File::get($path), true) : [];
        } else {
            // Security: Sanitize filename
            $filename = basename($file);
            $path = base_path("lang/{$locale}/{$filename}");
            $translations = File::exists($path) ? include($path) : [];
        }

        // 2. Load Baseline (EN) Translations
        $baselineTranslations = [];
        if ($locale !== 'en') {
            if ($file === 'json') {
                $baselinePath = base_path("lang/en.json");
                $baselineTranslations = File::exists($baselinePath) ? json_decode(File::get($baselinePath), true) : [];
            } else {
                $filename = basename($file);
                $baselinePath = base_path("lang/en/{$filename}");
                $baselineTranslations = File::exists($baselinePath) ? include($baselinePath) : [];
            }
        }

        // 3. Merge Keys to show EVERYTHING (Baseline + Current)
        // We want to show keys that are in EN but missing in Current
        // We also want to keep keys that are in Current but maybe not in EN (custom keys)
        $allKeys = array_unique(array_merge(array_keys($translations), array_keys($baselineTranslations)));
        sort($allKeys);

        // 4. Prepare Unified Data
        $combinedTranslations = [];
        $missingKeys = [];

        foreach ($allKeys as $key) {
            $currentValue = $translations[$key] ?? null;
            $baselineValue = $baselineTranslations[$key] ?? null;

            if ($currentValue === null && $baselineValue !== null) {
                $missingKeys[] = $key;
            }

            // If current value is missing, we might want to show empty string or null to let UI handle "placeholder"
            $combinedTranslations[$key] = [
                'value' => $currentValue, // Can be null if missing
                'baseline' => $baselineValue
            ];
        }

        // If sorting by key is desired, $combinedTranslations is already sorted by virtue of $allKeys loop if we constructed it that way,
        // but let's just pass the data.

        return view('settings.languages.edit', compact('locale', 'file', 'combinedTranslations', 'missingKeys', 'baselineTranslations'));
    }

    public function update(Request $request, $locale)
    {
        $file = $request->input('file', 'json');
        $request->validate([
            'keys' => 'required|array',
            'values' => 'required|array',
        ]);

        $keys = $request->input('keys');
        $values = $request->input('values');
        $translations = [];

        foreach ($keys as $index => $key) {
            // Allow empty values but skip empty keys
            if (!empty($key)) {
                $translations[$key] = $values[$index] ?? '';
            }
        }

        if ($file === 'json') {
            $path = base_path("lang/{$locale}.json");
            File::put($path, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } else {
            // Security: Sanitize filename
            $filename = basename($file);
            $path = base_path("lang/{$locale}/{$filename}");

            // Generate PHP file content
            $content = "<?php\n\nreturn " . $this->varExportShort($translations) . ";\n";
            File::put($path, $content);
        }

        return back()->with('success', 'Çeviriler güncellendi.');
    }

    /**
     * Custom var_export using short array syntax []
     */
    private function varExportShort($expression, $return = true)
    {
        $export = var_export($expression, true);
        $export = preg_replace("/^([ ]*)(.*)/m", '$1$1$2', $export);
        $array = preg_replace("/\r\n|\r|\n/", "\n", $export);
        $array = preg_replace("/array \( (.*) \)/s", '[$1]', $array);
        $array = preg_replace("/^([ ]*)\)(,?)$/m", '$1]$2', $array);
        $array = preg_replace("/=>[ ]?\n[ ]+\[/", '=> [', $array);
        $array = preg_replace("/([ ]*)(\'[^\']+\') => ([\[\'])/", '$1$2 => $3', $array);

        if ($return) {
            return $array;
        } else {
            echo $array;
        }
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
