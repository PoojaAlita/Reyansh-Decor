<?php

namespace App\Providers;

use App\Models\AdminPage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{DB, View, Schema};
use App\Helpers\MenuBuilder;



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
        // View::composer('*', function ($view) {
        // $menuData = \App\Models\AdminPage::orderBy('sortorder')->get();
        //     $view->with('menuData', $menuData);
        // });
            View::share('pages', AdminPage::orderBy('sortorder')->get());

        
    }
}
