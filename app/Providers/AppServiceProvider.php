<?php

namespace App\Providers;

use App\Contracts\ExceptionServiceInterface;
use App\Contracts\PdfGeneratorInterface;
use App\Contracts\PrintServiceInterface;
use App\Contracts\QrcodeGeneratorServiceInterface;
use App\Core\KTBootstrap;
use App\Models\Commune;
use App\Models\Year;
use App\Services\ExceptionService;
use App\Services\PdfGeneratorService;
use App\Services\PrintService;
use App\Services\QrcodeGeneratorService;
use Illuminate\Database\Schema\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
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
        $this->app->singleton(QrcodeGeneratorServiceInterface::class, fn()=> new QrcodeGeneratorService());
        $this->app->singleton(PrintServiceInterface::class, fn()=> new PrintService());
        $this->app->singleton(PdfGeneratorInterface::class, fn()=> new PdfGeneratorService(
            Commune::getFirstCommune(),
            $this->app->make(QrcodeGeneratorServiceInterface::class)
        ));
        $this->app->bind(
            ExceptionServiceInterface::class,
            fn(Application $app) =>  $app->make(ExceptionService::class)
        );
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
            $commune = cache()->rememberForever('first_commune', function () {
                $c = Commune::getFirstCommune();
                return $c ?: null;
            });
            if (!$commune) {
                $commune = Commune::getFirstCommune();
                if ($commune) {
                    cache()->forever('first_commune', $commune);
                }
            }

            $year = cache()->rememberForever('active_year', function () {
                $y = Year::getActiveYear();
                return $y ?: null;
            });

            if (!$year) {
                $year = Year::getActiveYear();
                if ($year) {
                    cache()->forever('active_year', $year);
                }
            }

            $public_ip= '';
            $month = $year
                ? Carbon::createFromFormat('m', $year->current_month)->monthName
                : null;
            $view->with(compact('public_ip','commune', 'year', 'month'));
        });

        KTBootstrap::init();
    }
}
