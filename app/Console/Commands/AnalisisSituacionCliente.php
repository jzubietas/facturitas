<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AnalisisSituacionCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show:analisis:situacion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
      //obtener pedido mas antiguo para calcalcular primer pedido  periodo mes_anio

      $this->warn("Cargando primer pedido mes anio");
      $fp=Pedido::orderBy('created_at','asc')->limit(1)->first();

      $this->info("Primer periodo del primer pedido es " .Carbon::parse($fp->created_at)->format('Y_m') );
      $primer_periodo=Carbon::parse($fp->created_at);//->format('Y_m');
      $periodo_actual=Carbon::parse(now());//->format('Y_m');

      $diff = $primer_periodo->diffInMonths($periodo_actual);
      $this->info("Diferencia de meses ".$diff);
      return 0;


      $clientes=Cliente::whereIn('tipo',['0','1'])->orderBy('id')->get();
      foreach($clientes as $cliente)
      {
        //analisis de sus pedidos
        $idcliente=$cliente->id;

        if($cliente->id==1)
        {
          $this->info($cliente->nombre);
          break;
        }
      }
      $this->info('FIN');
      //


      //select * from pedidos order by created_at  asc limit 1

      //$date = Carbon::createFromDate(1970,19,12)->age; // 43


        return 0;
    }
}
