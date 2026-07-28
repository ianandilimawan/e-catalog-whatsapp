<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.env') === 'production' || config('app.env') === 'staging') {
            $this->app['request']->server->set('HTTPS', true);
        }


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        try {
            $settings = Setting::first();
            if ($settings && $settings->smtp_host) {
                config([
                    'mail.mailers.smtp.host' => $settings->smtp_host,
                    'mail.mailers.smtp.port' => $settings->smtp_port,
                    'mail.mailers.smtp.encryption' => $settings->smtp_encryption,
                    'mail.mailers.smtp.username' => $settings->smtp_username,
                    'mail.mailers.smtp.password' => $settings->smtp_password,
                    'mail.from.address' => $settings->smtp_from_address,
                    'mail.from.name' => $settings->smtp_from_name,
                ]);
            }
        } catch (\Exception $e) {
            // Ignore if table doesn't exist yet
        }

        // Share settings to all admin views (including guest views like login)
        View::composer('admin.*', function ($view) {
            // Share settings to all admin views
            $settings = Setting::getSettings();
            $view->with('settings', $settings);
        });

        // Share menus to all admin views - filter by user permissions
        View::composer('admin.*', function ($view) {
            $user = Auth::user();

            $filterMenus = function ($items) use (&$filterMenus, $user) {
                return collect($items)->filter(function ($item) use ($user) {
                    if (!$user) {
                        return false;
                    }
                    if ($user->hasRole(['administrator', 'admin'])) {
                        return true;
                    }
                    if (!empty($item['permission'])) {
                        try {
                            return $user->hasPermissionTo($item['permission']);
                        } catch (\Exception $e) {
                            return false; // Permission doesn't exist
                        }
                    }
                    return true;
                })->map(function ($item) use (&$filterMenus) {
                    if (!empty($item['children'])) {
                        $item['children'] = $filterMenus($item['children'])->values()->toArray();
                    }
                    return $item;
                });
            };

            $groupedMenus = collect(config('menu', []))->map(function ($items, $section) use ($filterMenus) {
                return $filterMenus($items)->values();
            })->filter(function ($items) {
                return $items->count() > 0;
            });

            $view->with('groupedMenus', $groupedMenus);

            // Share settings to all admin views
            $settings = Setting::getSettings();
            $view->with('settings', $settings);
        });
    }
}
