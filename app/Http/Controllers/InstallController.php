<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\License; // Assuming we use License model for storing key
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function __construct()
    {
        // Check if already installed
        if (File::exists(storage_path('installed'))) {
            abort(403, 'Application is already installed.');
        }
    }

    public function welcome()
    {
        return view('install.welcome');
    }

    public function requirements()
    {
        $requirements = [
            'PHP Version >= 8.2' => version_compare(phpversion(), '8.2.0', '>='),
            'BCMath Extension' => extension_loaded('bcmath'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('pdo'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
        ];

        $allMet = !in_array(false, $requirements);

        return view('install.requirements', compact('requirements', 'allMet'));
    }

    public function permissions()
    {
        $permissions = [
            'storage/app' => is_writable(storage_path('app')),
            'storage/framework' => is_writable(storage_path('framework')),
            'storage/logs' => is_writable(storage_path('logs')),
            'bootstrap/cache' => is_writable(base_path('bootstrap/cache')),
            '.env' => is_writable(base_path('.env')),
        ];

        $allMet = !in_array(false, $permissions);

        return view('install.permissions', compact('permissions', 'allMet'));
    }

    public function database()
    {
        return view('install.database');
    }

    public function saveDatabase(Request $request)
    {
        $request->validate([
            'host' => 'required',
            'port' => 'required',
            'database' => 'required',
            'username' => 'required',
        ]);

        try {
            $connection = new \PDO(
                "mysql:host={$request->host};port={$request->port}",
                $request->username,
                $request->password
            );

            // Check if database exists, if not try to create
            $connection->exec("CREATE DATABASE IF NOT EXISTS `{$request->database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

            // Update .env
            $this->updateEnv([
                'DB_HOST' => $request->host,
                'DB_PORT' => $request->port,
                'DB_DATABASE' => $request->database,
                'DB_USERNAME' => $request->username,
                'DB_PASSWORD' => $request->password ?? '',
            ]);

            return redirect()->route('install.license')->with('success', 'Database connection successful.');

        } catch (\Exception $e) {
            return back()->with('error', 'Database connection failed: ' . $e->getMessage());
        }
    }

    public function license()
    {
        return view('install.license');
    }

    public function verifyLicense(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string',
            'client_name' => 'required|string',
        ]);

        $licenseKey = $request->license_key;
        $clientName = $request->client_name;
        $domain = $request->getHost();
        $ip = $request->ip();

        try {
            // Call Master API
            // In production, this URL should be https://master.mioly.com/api/master/verify-license
            // For dev/hybrid, we use the current APP_URL or a configured MASTER_API_URL
            $masterApiUrl = config('app.master_api_url', url('/api/master/verify-license'));

            $response = \Illuminate\Support\Facades\Http::timeout(15)->post($masterApiUrl, [
                'license_code' => $licenseKey,
                'domain' => $domain,
                'ip' => $ip,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (($data['status'] ?? '') === 'success') {
                    // Valid License
                    session([
                        'license_key' => $licenseKey,
                        'client_name' => $clientName,
                        'license_data' => $data['data'] ?? []
                    ]);

                    return redirect()->route('install.admin')->with('success', 'Lisans doğrulandı: ' . ($data['data']['type'] ?? 'Standard'));
                } else {
                    return back()->with('error', 'Lisans hatası: ' . ($data['message'] ?? 'Bilinmeyen hata'));
                }
            } else {
                return back()->with('error', 'Doğrulama sunucusu hatası: ' . $response->status());
            }

        } catch (\Exception $e) {
            // For development fallback if API is unreachable (self-hosted deadlock)
            if (app()->isLocal()) {
                session([
                    'license_key' => $licenseKey,
                    'client_name' => $clientName,
                    'license_data' => ['type' => 'Dev-Local']
                ]);
                return redirect()->route('install.admin')->with('warning', 'Dev Modu: Lisans doğrulama atlandı (API erişilemiyor).');
            }

            return back()->with('error', 'Lisans sunucusuna bağlanılamadı: ' . $e->getMessage());
        }
    }

    public function admin()
    {
        return view('install.admin');
    }

    public function saveAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|confirmed|min:4',
        ]);

        // Run Migrations & Seeds
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        } catch (\Exception $e) {
            return back()->with('error', 'Migration failed: ' . $e->getMessage());
        }

        // Create Admin User (Update default or create new)
        // Since we seeded, likely ID 1 exists. Let's update it or create.
        User::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]
        );

        // Store License in DB if table exists (it should after migration)
        if (session('license_key')) {
            try {
                License::updateOrCreate(
                    ['license_key' => session('license_key')],
                    [
                        'client_name' => session('client_name'),
                        'domain' => request()->getHost(),
                        'status' => 'active',
                        'type' => session('license_data')['type'] ?? 'standard',
                        'last_check_at' => now(),
                    ]
                );
            } catch (\Exception $e) {
                // Log error but continue installation
                \Illuminate\Support\Facades\Log::error('License save failed: ' . $e->getMessage());
            }
        }

        return redirect()->route('install.finish');
    }

    public function finish()
    {
        // Create installed file
        File::put(storage_path('installed'), 'MIONEX Installed on ' . now());

        Artisan::call('storage:link');
        Artisan::call('optimize:clear');

        return view('install.finish');
    }

    private function updateEnv($data)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $content = file_get_contents($path);
            foreach ($data as $key => $value) {
                // Wrap value in quotes if it contains spaces
                if (strpos($value, ' ') !== false && strpos($value, '"') === false) {
                    $value = '"' . $value . '"';
                }

                if (strpos($content, "{$key}=") !== false) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
                } else {
                    $content .= "\n{$key}={$value}";
                }
            }
            file_put_contents($path, $content);
        }
    }
}
