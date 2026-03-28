<?php

namespace App\Providers;

use App\Contracts\ExceptionServiceInterface;
use App\Contracts\PdfGeneratorInterface;
use App\Contracts\PrintServiceInterface;
use App\Contracts\QrcodeGeneratorServiceInterface;
use App\Core\KTBootstrap;
use App\Models\Commune;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Year;
use App\Observers\InvoiceItemObserver;
use App\Observers\PaymentObserver;
use App\Services\ExceptionService;
use App\Services\MobilePayment\MobilePaymentProviderFactory;
use App\Services\MobilePayment\MobilePaymentService;
use App\Services\PdfGeneratorService;
use App\Services\PrintService;
use App\Services\QrcodeGeneratorService;
use App\Services\SmsService;
use Illuminate\Database\Schema\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
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
        $this->app->singleton(QrcodeGeneratorServiceInterface::class, fn()=> new QrcodeGeneratorService());
        $this->app->singleton(PrintServiceInterface::class, fn()=> new PrintService());
        $this->app->bind(PdfGeneratorInterface::class, fn()=> new PdfGeneratorService(
            Commune::getFirstCommune(),
            $this->app->make(QrcodeGeneratorServiceInterface::class)
        ));
        $this->app->bind(
            ExceptionServiceInterface::class,
            fn(Application $app) =>  $app->make(ExceptionService::class)
        );

        $this->app->singleton(MobilePaymentProviderFactory::class);
        $this->app->singleton(MobilePaymentService::class, fn(Application $app) => new MobilePaymentService(
            $app->make(MobilePaymentProviderFactory::class),
        ));
        $this->app->singleton(SmsService::class);
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);

        InvoiceItem::observe(InvoiceItemObserver::class);
        Payment::observe(PaymentObserver::class);

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
                return Commune::getFirstCommune() ?: null;
            });
            $year = cache()->rememberForever('active_year', function () {
                return Year::getActiveYear() ?: null;
            });
            if (!$commune) {
                $commune = Cache::rememberForever('first_commune', fn() => Commune::getFirstCommune());
            }
            if (!$year) {
                Cache::rememberForever('active_year', fn() => Year::getActiveYear());
            }
            $month = $year
                ? Carbon::createFromFormat('m', $year->current_month)->monthName
                : null;
            $view->with([
                'public_ip' => '',
                'commune'   => $commune,
                'year'      => $year,
                'month'     => $month,
            ]);
        });

        KTBootstrap::init();
    }
}
