<?php

namespace App\Providers;

use App\Observers\UserSecurityObserver;
use App\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserSecurityObserver::class);
        Blade::if('role', function ($roles) {
            $user = auth()->user();
            if (!$user) {
                return false;
            }
            foreach (preg_split('/[|,]/', (string) $roles) as $role) {
                if ($user->hasRole(trim($role))) {
                    return true;
                }
            }
            return false;
        });
    }
}
