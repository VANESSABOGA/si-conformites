<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Emails de notification : uniquement la file "conformite"
        // (la table "jobs" est partagée avec le SI-RH).
        if (config('queue.default') === 'database') {
            $schedule->command('queue:work --queue=' . config('queue.connections.database.queue') . ' --stop-when-empty --tries=3 --max-time=55')
                ->everyMinute()
                ->withoutOverlapping();
        }

        // Clôture automatique des campagnes arrivées à échéance.
        $schedule->command('conformite:cloturer-campagnes')->dailyAt('00:10');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
