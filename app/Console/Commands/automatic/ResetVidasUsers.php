<?php

namespace App\Console\Commands\automatic;

use App\Models\HistorialVidas;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ResetVidasUsers extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'automatic:vidas:reset';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Resetear metas cada cambio de mes';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  {
    $this->warn("Comando : Reseteo vidas");
    $usuarios=User::where('estado',1)->orderBy('id','asc')->get();
    $progress = $this->output->createProgressBar($usuarios->count());
    foreach($usuarios as $user)
    {
      $this->warn($user->id);
      $user->update([
        'vidas_total' => 3,
        'vidas_restantes' => 3,
        'cant_vidas_cero' => 0,
      ]);
        HistorialVidas::create([
            'user_id'=>$user->id,
            'accion'=>'Reseteado vidas users por Consola: '.strval($user->cant_vidas_cero).' a '.strval($user->cant_vidas_cero+1),
            'created_at' => Carbon::now()
        ]);
      $progress->advance();
    }
    $this->info("Finalizando...");
    $progress->finish();
    $this->info('FIN');

  }
}
