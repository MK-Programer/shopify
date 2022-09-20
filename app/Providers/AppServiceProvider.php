<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // share data across all the views
        View::share('Name', 'Mostafa');
        // get the user name from auth
        // it takes an array of views that will be shared accross
        View::composer('*', function($view){
            $view->with('userData', Auth::user());
        });
        Paginator::useBootstrap();
    }
}
