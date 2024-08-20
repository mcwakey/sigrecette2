<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB; // Import the DB facade

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {
        // Define a scheduled task to run the stored procedure daily at a specific time
        $schedule->call(function () {
            DB::statement('CALL updateTaxpayerTaxables()');
        })->dailyAt('21:30'); // Adjust the time as needed
        $schedule->command('datatables:purge-export')->weekly();
        $schedule->command('backup:clean')->daily()->at('15:30');
        $schedule->command('backup:run')->daily()->at('16:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
