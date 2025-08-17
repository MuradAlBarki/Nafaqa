<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Late payment notification: run monthly at 8:00 AM on the 1st
        $schedule->command('app:notify-late-payments')->monthlyOn(1, '08:00');

        // Generate monthly payments: run monthly at midnight on the 1st
        $schedule->command('app:generate-monthly-payments')->monthlyOn(1, '00:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
