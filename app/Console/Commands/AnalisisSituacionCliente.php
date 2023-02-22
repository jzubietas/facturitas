<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\SituacionClientes;
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

    $this->warn("Cargando primer pedido mes anio");
    $fp=Pedido::orderBy('created_at','asc')->limit(1)->first();

    $periodo_original=Carbon::parse($fp->created_at);//->format('Y_m');
    $periodo_actual=Carbon::parse(now());//->format('Y_m');

    $primer_periodo=Carbon::parse($fp->created_at);
    $diff = ($periodo_original->diffInMonths($periodo_actual))+1;
    $this->info("Diferencia de meses ".$diff);

    $where_anio='';
    $where_mes='';
    $cont_mes=0;

    $clientes=Cliente::whereIn('tipo',['0','1'])->orderBy('id')->get();
    //$periodo_original=$primer_periodo;
    foreach($clientes as $cliente)
    {

      $idcliente=$cliente->id;

      if($cliente->id==1)
      {
        $this->info($cliente->nombre);
        $delete=SituacionClientes::where('cliente_id',$idcliente)->delete();
        $this->info("situacion en clientes limpio ");

        $periodo_inicial=Carbon::parse($fp->created_at);
        //$periodo_ejecucion=$periodo_inicial;
        $periodo_ejecucion=null;

        for($i=0;$i<$diff;$i++)
        {
          $this->info("suma meses : ".$i." a ".$periodo_inicial);
          $periodo_ejecucion=Carbon::parse($fp->created_at)->addMonths($i);

          $this->warn("periodo ejecucion: ".$periodo_ejecucion);

          $where_anio=$periodo_ejecucion->format('Y');
          $where_mes=$periodo_ejecucion->format('m');

          $this->info("where  ".$where_anio.' '.$where_mes);

          //contadores
          $cont_mes=Pedido::where('cliente_id',$idcliente)->whereYear('created_at',$where_anio)
            ->whereMonth('created_at',$where_mes)->count();
          $cont_mes_activo=Pedido::where('cliente_id',$idcliente)->whereYear('created_at',$where_anio)
            ->whereMonth('created_at',$where_mes)->activo()->count();
          $cont_mes_anulado=Pedido::where('cliente_id',$idcliente)->whereYear('created_at',$where_anio)
            ->whereMonth('created_at',$where_mes)->activo('0')->count();

          $situacion_create=SituacionClientes::create([
            'cliente_id'=>$idcliente,
            'situacion'=>'',
            'cantidad_pedidos'=>$cont_mes,
            'anulados'=>$cont_mes_anulado,
            'activos'=>$cont_mes_activo,
            'periodo'=>Carbon::createFromDate($where_anio, $where_mes)->startOfMonth()->format('Y-m')
          ]);

          $compara=Carbon::parse($fp->created_at);
          $mes_antes = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth()->subMonth();
          if($cont_mes==0)
          {
            if( $where_anio==$compara->format('Y') && $where_mes==$compara->format('m') )
            {
              //primer mes y contador 0
              $this->warn("es igual al primer periodo -".$cont_mes.' - SERA BASE FRIA ');
              $situacion_create->update([
                "situacion" => 'BASE FRIA'
              ]);
            }else{
              $this->warn('Mes antes '.$mes_antes->format('Y-m').' cliente '.$idcliente);
              $situacion_antes=SituacionClientes::where('cliente_id',$idcliente)->where('periodo',$mes_antes->format('Y-m'))->first()->situacion;
              $this->warn('Situacion en '.$mes_antes->format('Y-m').' fue '.$situacion_antes);

              switch($situacion_antes)
              {
                case 'BASE FRIA':break;
                case 'NUEVO':break;
                case 'ABANDONO':break;
                case 'NUEVO':break;
              }

              $situacion_create->update([
                "situacion" => 'BASE FRIA'
              ]);
            }
          }else{
            if( $where_anio==$compara->format('Y') && $where_mes==$compara->format('m') )
            {
              //primer mes y contador >0
              $this->warn("es igual al primer periodo -".$cont_mes.' - SERA NUEVO ');
              $situacion_create->update([
                "situacion" => 'NUEVO'
              ]);
            }else{
              $this->warn('Mes antes '.$mes_antes->format('Y-m'));
              $situacion_antes=SituacionClientes::where('cliente_id',$idcliente)->where('periodo',$mes_antes->format('Y-m'))->first()->situacion;
              $this->warn('Situacion en '.$mes_antes->format('Y-m').' fue '.$situacion_antes);
            }
          }



        }
        //continue;

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
