<?php

namespace App\Providers;

use App\Core\KTBootstrap;
use App\Models\Commune;
use App\Services\GetPublicService;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\App;
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
        Blade::if('feature', function ($feature) {
            if (App::environment('production')) {
                return config('features.' . $feature);
            }
            return true;
        });
        View::composer('*', function ($view) {
            $view->with('commune', Commune::getFirstCommune());
            $view->with('public_ip', '');
        });
        KTBootstrap::init();
    }
}
