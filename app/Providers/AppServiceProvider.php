<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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

        // Share System Settings with all views
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
                $siteSettings = \App\Models\SystemSetting::firstOrCreate(['id' => 1]);

                // Force MIONEX branding if defaults are present
                if ($siteSettings->site_name === 'Mioly' || $siteSettings->site_name === 'Laravel') {
                    $siteSettings->update(['site_name' => 'MIONEX']);
                }

                if (\Illuminate\Support\Facades\Schema::hasTable('brand_settings')) {
                    $brandTitle = \App\Models\BrandSetting::where('key', 'site_title')->first();
                    if (!$brandTitle || $brandTitle->value === 'Mioly' || $brandTitle->value === 'Laravel') {
                        \App\Models\BrandSetting::updateOrCreate(['key' => 'site_title'], ['value' => 'MIONEX']);
                    }
                }

                \Illuminate\Support\Facades\View::share('siteSettings', $siteSettings);
                \Illuminate\Support\Facades\View::share('brandSettings', \App\Models\BrandSetting::all()->pluck('value', 'key')->toArray());
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet (Installer mode)
        }

        // Share Brand Settings
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('brand_settings')) {
                \Illuminate\Support\Facades\View::composer('*', function ($view) {
                    $brandSettings = \Illuminate\Support\Facades\Cache::rememberForever('brand_settings', function () {
                        return \App\Models\BrandSetting::all()->pluck('value', 'key');
                    });
                    $view->with('brandSettings', $brandSettings);
                });
            }
        } catch (\Exception $e) {
            // Table doesn't exist yet
        }
    }
}
