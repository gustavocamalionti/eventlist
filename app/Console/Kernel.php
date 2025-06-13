<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Services\Site\Rules\RulesUpdateBuysService;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(new RulesUpdateBuysService())->dailyAt("04:00");
        // $schedule->call(new RulesUpdateBuysService())->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . "/Commands");

        require base_path("routes/common/console.php");
    }
}
