<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use App\Jobs\SyncExportInvoicesJob;
use App\Jobs\SyncImportPaymentsJob;

// Import the DB facade

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {
        // Define a scheduled task to run the stored procedure daily at a specific time
        $schedule->call(function () {
            DB::statement('CALL updateTaxpayerTaxables()');
        })->dailyAt('8:30');
        $schedule->command('datatables:purge-export')->daily()->at('16:50');
        $schedule->command('backup:clean')->weekly()->at('8:00');
        $schedule->command('backup:run')->daily()->at('9:00');
        $schedule->command('backup:run')->daily()->at('12:00');
        $schedule->command('backup:run')->daily()->at('15:00');
        $schedule->command('backup:run')->daily()->at('17:00');
        $schedule->command('backup:run')->daily()->at('17:30');
        $schedule->command('backup:run')->daily()->at('20:00');

        $schedule->call(function () {DB::statement('CALL updateTaxpayerTaxables()');})->dailyAt('17:00');
        $schedule->call(function () {DB::statement('CALL updateTaxpayerTaxables()');})->dailyAt('20:00');

        $schedule->job(new SyncExportInvoicesJob())->cron(config('sync.export_cron'));
        $schedule->job(new SyncImportPaymentsJob())->cron(config('sync.import_cron'));

        $schedule->call(function () {
            DB::statement('CALL updateTaxpayerTaxables()');
        })->dailyAt('17:00');
        $schedule->call(function () {
            DB::statement('CALL updateTaxpayerTaxables()');
        })->dailyAt('20:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
