<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        View::composer('admin.partials.topbar', function ($view) {
            if (!auth()->check()) {
                return;
            }

            $view->with(
                'notifications',
                auth()
                    ->user()
                    ->notifications()
                    ->latest()
                    ->take(10)
                    ->get()
            );

            $view->with(
                'unreadCount',
                auth()
                    ->user()
                    ->unreadNotifications()
                    ->count()
            );
        });
    }
}
