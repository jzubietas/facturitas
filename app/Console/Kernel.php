<?php

namespace App\Console;

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
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('fixed:bin')->everyMinute();

        $schedule->command('backup:database')
            ->dailyAt('19:30');

        $schedule->command('backup:database')
            ->dailyAt('13:15');

        $schedule->command('motorizado:auto-no-recibido', [
            '--all',
        ])
            ->dailyAt('15:00');

        $schedule->command('motorizado:send-observado')
            ->dailyAt('23:59');

        $schedule->command('olva:sync')
            ->dailyAt('20:00');
/*
        $schedule->command('olva:move-tienda-agente')
            ->dailyAt('00:00');

        $schedule->command('olva:encargado:tienda_reset')
            ->dailyAt('00:10');
*/
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
