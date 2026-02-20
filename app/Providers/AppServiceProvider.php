<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix MySQL utf8mb4 index key length issue
        Schema::defaultStringLength(191);

        // Force HTTPS in production (Railway)
        if (config('app.env') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Model Observers
        \App\Models\Invoice::observe(\App\Observers\InvoiceObserver::class);
        \App\Models\Payment::observe(\App\Observers\PaymentObserver::class);

        // Activity Logging for Login/Logout
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            function ($event) {
                \App\Models\ActivityLog::create([
                    'actor_user_id' => $event->user->id,
                    'action' => 'user.login',
                    'metadata' => ['ip' => request()->ip()],
                ]);
            }
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            function ($event) {
                if ($event->user) {
                    \App\Models\ActivityLog::create([
                        'actor_user_id' => $event->user->id,
                        'action' => 'user.logout',
                    ]);
                }
            }
        );

        // Email Logging
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Mail\Events\MessageSent::class,
            function ($event) {
                $message = $event->message;

                $to = [];
                foreach ($message->getTo() as $address) {
                    $to[] = $address->getAddress();
                }

                \App\Models\EmailLog::create([
                    'to' => implode(', ', $to),
                    'subject' => $message->getSubject(),
                    'body' => $message->getHtmlBody() ?? $message->getTextBody(), // Symfony Mailer
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            }
        );

        // Share System Settings with all views
        try {
            $hasSystemSettingsTable = \Illuminate\Support\Facades\Cache::rememberForever('schema_has_system_settings', function () {
                return \Illuminate\Support\Facades\Schema::hasTable('system_settings');
            });

            if ($hasSystemSettingsTable) {
                $siteSettings = \App\Models\SystemSetting::firstOrCreate(['id' => 1]);

                // Force MIONEX branding if defaults are present
                if ($siteSettings->site_name === 'Mioly' || $siteSettings->site_name === 'Laravel') {
                    $siteSettings->update(['site_name' => 'MIONEX']);
                }

                $hasBrandSettingsTable = \Illuminate\Support\Facades\Cache::rememberForever('schema_has_brand_settings', function () {
                    return \Illuminate\Support\Facades\Schema::hasTable('brand_settings');
                });

                if ($hasBrandSettingsTable) {
                    $brandTitle = \App\Models\BrandSetting::where('key', 'site_title')->first();
                    if (!$brandTitle || $brandTitle->value === 'Mioly' || $brandTitle->value === 'Laravel') {
                        \App\Models\BrandSetting::updateOrCreate(['key' => 'site_title'], ['value' => 'MIONEX']);
                    }
                }

                \Illuminate\Support\Facades\View::share('siteSettings', $siteSettings);

                // Dynamic Configuration Boot
                config([
                    'app.timezone' => $siteSettings->timezone ?? config('app.timezone'),
                    'app.locale' => $siteSettings->locale ?? config('app.locale'),
                ]);
                date_default_timezone_set(config('app.timezone'));
                app()->setLocale(config('app.locale'));

                $brandSettings = \Illuminate\Support\Facades\Cache::rememberForever('brand_settings_all', function () {
                    return \App\Models\BrandSetting::all()->pluck('value', 'key')->toArray();
                });
                \Illuminate\Support\Facades\View::share('brandSettings', $brandSettings);
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet (Installer mode)
        }

        // Share Brand Settings
        try {
            $hasBrandSettingsTable = \Illuminate\Support\Facades\Cache::rememberForever('schema_has_brand_settings', function () {
                return \Illuminate\Support\Facades\Schema::hasTable('brand_settings');
            });

            if ($hasBrandSettingsTable) {
                \Illuminate\Support\Facades\View::composer('*', function ($view) {
                    $brandSettings = \Illuminate\Support\Facades\Cache::rememberForever('brand_settings_all', function () {
                        return \App\Models\BrandSetting::all()->pluck('value', 'key');
                    });
                });
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet
        }

        // Load SMTP Settings from Database
        try {
            $hasEmailSettingsTable = \Illuminate\Support\Facades\Cache::rememberForever('schema_has_email_settings', function () {
                return \Illuminate\Support\Facades\Schema::hasTable('email_settings');
            });

            if ($hasEmailSettingsTable) {
                $emailSettings = \App\Models\EmailSetting::first();
                if ($emailSettings && $emailSettings->host) {
                    config([
                        'mail.mailers.smtp.host' => $emailSettings->host,
                        'mail.mailers.smtp.port' => $emailSettings->port,
                        'mail.mailers.smtp.username' => $emailSettings->username,
                        'mail.mailers.smtp.password' => $emailSettings->password_encrypted ? \Illuminate\Support\Facades\Crypt::decryptString($emailSettings->password_encrypted) : null,
                        'mail.mailers.smtp.encryption' => $emailSettings->encryption,
                        'mail.from.address' => $emailSettings->from_email,
                        'mail.from.name' => $emailSettings->from_name,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Ignore during migration or if table missing
        }
    }
}
