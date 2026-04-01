<?php

namespace App\Providers;

use App\Models\Master_info;
use App\Models\Master_setting;
use Illuminate\Support\Facades\Auth;
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
        view()->share('setting', Master_setting::first());

        // Notifikasi
        view()->share('notifs', function () {

            if (Auth::check() && Auth::user()->role === 'user') {
                return Master_info::where('status', 'aktif')
                    ->whereDoesntHave('readers', function ($q) {
                        $q->where('user_id', Auth::id());
                    })
                    ->latest()
                    ->take(5)
                    ->get();
            }

            return collect(); // admin = kosong
        });

        view()->share('notifCount', function () {

            if (Auth::check() && Auth::user()->role === 'user') {
                return Master_info::where('status', 'aktif')
                    ->whereDoesntHave('readers', function ($q) {
                        $q->where('user_id', Auth::id());
                    })
                    ->count();
            }

            return 0;
        });
    }
}
