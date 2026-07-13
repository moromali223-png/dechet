<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ==================== TES TÂCHES ICI ====================
        $schedule->command('eco:generate-next-planifications')
                 ->dailyAt('01:00')
                 ->withoutOverlapping();

        // Tu peux ajouter d'autres tâches plus bas si tu en as
        // Exemple :
        // $schedule->command('backup:clean')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}