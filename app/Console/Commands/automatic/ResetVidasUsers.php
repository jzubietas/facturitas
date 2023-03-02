<?php

namespace App\Console\Commands\automatic;

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
    $fp=Pedido::orderBy('created_at','asc')->limit(1)->first();
    $periodo_original=Carbon::parse($fp->created_at)->startOfMonth();
    $periodo_actual=Carbon::parse(now())->endOfMonth();

    $this->warn( $periodo_original );
    $this->info( $periodo_actual );

    $diff = ($periodo_original->diffInMonths($periodo_actual))+1;
    $this->warn( $diff );
    /*if (now()==$periodo_actual || now()==$periodo_actual ){}*/
    /*Listado de los  (*10) usuarios ordernados por id asc*/
    $usuarios=User::where('estado',1)->orderBy('id','asc')->offset(0)->limit(10)->get();
    $progress = $this->output->createProgressBar($usuarios->count());
    foreach($usuarios as $user)
    {
      $this->warn($user->id);
      $user->update([
        'vidas_total' => 3,
        'vidas_restantes' => 3,
        'cant_vidas_cero' => 0,
      ]);
      $progress->advance();
    }
    $this->info("Finalizando...");
    $progress->finish();
    $this->info('FIN');

  }
}
