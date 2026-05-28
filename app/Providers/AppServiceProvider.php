<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use Illuminate\Pagination\Paginator; 
use App\Models\Logo;            

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }
    public function boot(): void
    {       
        Paginator::useBootstrapFive();
        View::composer('*', function ($view) {
            $view->with('globalLogo', Logo::latest()->first());
        });
    }
}