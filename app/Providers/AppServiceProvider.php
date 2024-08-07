<?php

namespace App\Providers;

use App\Core\KTBootstrap;
use App\Models\Commune;
use App\Services\GetPublicService;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Blade;
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
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);
        Blade::directive('numberToWords', function ($number) {
            return "";
        });
        View::composer('*', function ($view) {
            $view->with('commune', Commune::getFirstCommune());
            $view->with('public_ip', '');
        });
        KTBootstrap::init();
    }
}
