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
        $schedule->command('fixed:bin')->everyThirtyMinutes();

        $schedule->command('backup:database')
            ->dailyAt('20:00');

        $schedule->command('backup:database')
            ->dailyAt('13:15');

        $schedule->command('motorizado:auto-no-recibido', [
            '--all',
        ])
            ->dailyAt('15:00');

        $schedule->command('motorizado:send-observado')
            ->dailyAt('23:59');

        $schedule->command('actualizaestado:masivo')
            ->dailyAt('04:00');

        $schedule->command('actualizaestado:masivo')
            ->dailyAt('13:00');

        $schedule->command('show:analisis:situacion')
            ->monthlyOn(1,'01:00');
        /*$schedule->command('olva:sync')
            ->dailyAt('20:00');*/

        //Resetea las metas de los asesores el primer dia del mes
        $schedule->command('automatic:metas:reset')->monthlyOn(1, '00:01');;

        //Resetea las vidas en general los dias 15 y el ultimo dia del mes
        /*
        $schedule->command('automatic:vidas:reset')->monthlyOn(15,'23:59');;
        $schedule->command('automatic:vidas:reset')->monthly();
        */

        /*
         * $schedule->command('command:vidas.admin')->dailyAt('14:40');
        */
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
