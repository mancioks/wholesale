<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        Blade::if('role', function (...$roles) {
            if (auth()->check()) {
                $roles = empty($roles) ? [null] : $roles;

                foreach ($roles as $role) {
                    if (auth()->user()->role->name === $role) {
                        return true;
                    }
                }
            }
            return false;
        });

        Blade::if('activated', function () {
            return auth()->user()->activated;
        });

        Blade::if('prefix', function ($prefix) {
            return request()->is($prefix.'*');
        });

        Blade::if('route', function (...$routes) {
            foreach ($routes as $route) {
                if (Route::currentRouteName() === $route) {
                    return true;
                }
            }
            return false;
        });
    }
}
