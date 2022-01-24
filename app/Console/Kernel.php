<?php

namespace App\Console;

use App\Console\Commands\ImportStockItemsFromFeed;
use App\Console\Commands\RemoveOldFormSubmissions;
use App\Console\Commands\RemoveOldVacancyApplications;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(RemoveOldFormSubmissions::class)->daily();
        $schedule->command(RemoveOldVacancyApplications::class)->daily();
        $schedule->command(ImportStockItemsFromFeed::class)->everyThirtyMinutes();
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
