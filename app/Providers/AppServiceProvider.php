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
        View::composer('*', function ($view) {
        $menuData = AdminPage::where('isshown',1)->orderBy('sortorder')->get();

        $menuTree = MenuBuilder::build($menuData);

        $view->with('menuTree', $menuTree);
    
    
    });

        
    }
}
