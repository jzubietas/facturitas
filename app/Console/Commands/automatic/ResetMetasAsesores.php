<?php

namespace App\Console\Commands\automatic;

use Illuminate\Console\Command;

class ResetMetasAsesores extends Command
{
  protected $signature = 'asesores:reset-metas';
  protected $description = 'Reseteo de metas para los asesores';
  public function __construct()
  {
    parent::__construct();
  }

  public function handle()
  {
    $now = Carbon::now();
    echo $now->year;
    echo $now->month;
    echo $now->week;

    /*
     * Carbon::now() monthstart(today(),-5)
     * if (now() < now()->isEndOfDay()) {
      $ask = $this->confirm("Se esta ejecutando antes de las 11:59 M,¿Continuar?");
      if (!$ask) {
        return 0;
      }
    }*/
    return $now;
  }
}
