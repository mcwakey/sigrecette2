<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\PackageVersionService;

class VersionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Register the service here if necessary
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(PackageVersionService $packageVersionService)
    {
        try {
            $version = $packageVersionService->getVersion();
            View::share('projectVersion', $version);
        } catch (\Exception $e) {
            View::share('projectVersion', '0.0.0');
        }
    }
}
