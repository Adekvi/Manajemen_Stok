<?php

namespace App\Providers;

use App\Models\Master_info;
use App\Models\Master_setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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
        Blade::if('role', function ($role) {
            /** @var User $user */
            $user = Auth::user();

            return $user && $user->hasRole($role);
        });

        // Menu
        View::composer('*', function ($view) {
            if (Auth::check()) {

                $cacheKey = 'sidebar_user_' . Auth::id();

                $sidebarMenus = cache()->remember($cacheKey, 300, function () {
                    return Auth::user()->menus; // ✅ sudah grouped dari model
                });

                $view->with('sidebarMenus', $sidebarMenus);
            } else {
                $view->with('sidebarMenus', collect());
            }
        });

        // Master Setting
        view()->share('setting', Master_setting::first());

        View::composer('*', function ($view) {

            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            if (!$user || !$user->hasRole('user')) {
                $view->with('notifData', [
                    'items' => collect(),
                    'count' => 0
                ]);
                return;
            }

            $query = Master_info::where('status', 'aktif')
                ->whereDoesntHave('readers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });

            $view->with('notifData', [
                'items' => (clone $query)->latest()->take(5)->get(),
                'count' => $query->count()
            ]);
        });
    }
}
