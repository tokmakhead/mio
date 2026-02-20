<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Log;

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
        try {
            // Fix MySQL utf8mb4 index key length issue
            Schema::defaultStringLength(191);

            // Force HTTPS in production
            if (config('app.env') === 'production') {
                URL::forceScheme('https');
            }

            // Model Observers
            if (class_exists(\App\Models\Invoice::class) && class_exists(\App\Observers\InvoiceObserver::class)) {
                \App\Models\Invoice::observe(\App\Observers\InvoiceObserver::class);
            }
            if (class_exists(\App\Models\Payment::class) && class_exists(\App\Observers\PaymentObserver::class)) {
                \App\Models\Payment::observe(\App\Observers\PaymentObserver::class);
            }

            // Activity Logging for Login/Logout
            Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
                try {
                    \App\Models\ActivityLog::create([
                        'actor_user_id' => $event->user->id,
                        'action' => 'user.login',
                        'metadata' => ['ip' => request()->ip()],
                    ]);
                } catch (\Exception $e) {
                }
            });

            Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
                try {
                    if ($event->user) {
                        \App\Models\ActivityLog::create([
                            'actor_user_id' => $event->user->id,
                            'action' => 'user.logout',
                        ]);
                    }
                } catch (\Exception $e) {
                }
            });

            // Email Logging
            Event::listen(\Illuminate\Mail\Events\MessageSent::class, function ($event) {
                try {
                    $message = $event->message;
                    $to = [];
                    foreach ($message->getTo() as $address) {
                        $to[] = $address->getAddress();
                    }

                    \App\Models\EmailLog::create([
                        'to' => implode(', ', $to),
                        'subject' => $message->getSubject(),
                        'body' => $message->getHtmlBody() ?? $message->getTextBody(),
                        'status' => 'sent',
                        'sent_at' => now(),
                    ]);
                } catch (\Exception $e) {
                }
            });

            // Share System Settings with all views
            if (Schema::hasTable('system_settings')) {
                $siteSettings = \App\Models\SystemSetting::firstOrCreate(['id' => 1]);

                // Force MIONEX branding if defaults are present
                if ($siteSettings->site_name === 'Mioly' || $siteSettings->site_name === 'Laravel') {
                    $siteSettings->update(['site_name' => 'MIONEX']);
                }

                if (Schema::hasTable('brand_settings')) {
                    $brandTitle = \App\Models\BrandSetting::where('key', 'site_title')->first();
                    if (!$brandTitle || $brandTitle->value === 'Mioly' || $brandTitle->value === 'Laravel') {
                        \App\Models\BrandSetting::updateOrCreate(['key' => 'site_title'], ['value' => 'MIONEX']);
                    }
                }

                View::share('siteSettings', $siteSettings);

                config([
                    'app.timezone' => $siteSettings->timezone ?? config('app.timezone'),
                    'app.locale' => $siteSettings->locale ?? config('app.locale'),
                ]);
                date_default_timezone_set(config('app.timezone'));
                app()->setLocale(config('app.locale'));

                $brandSettings = Cache::rememberForever('brand_settings_all', function () {
                    try {
                        return \App\Models\BrandSetting::all()->pluck('value', 'key')->toArray();
                    } catch (\Exception $e) {
                        return [];
                    }
                });
                View::share('brandSettings', $brandSettings);
            }

            // Share Brand Settings via composer (safe)
            if (Schema::hasTable('brand_settings')) {
                View::composer('*', function ($view) {
                    try {
                        $brandSettings = Cache::rememberForever('brand_settings_all', function () {
                            return \App\Models\BrandSetting::all()->pluck('value', 'key')->toArray();
                        });
                        $view->with('brandSettings', $brandSettings);
                    } catch (\Exception $e) {
                    }
                });
            }

            // Load SMTP Settings from Database
            if (Schema::hasTable('email_settings')) {
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
            Log::error('AppServiceProvider boot error: ' . $e->getMessage());
        }
    }
}
