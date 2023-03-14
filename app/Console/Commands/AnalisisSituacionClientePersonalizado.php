<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\SituacionClientes;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AnalisisSituacionClientePersonalizado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show:analisis:situacion:personalizado';

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

    //$this->warn("Cargando primer pedido mes anio");
    $fp=Pedido::orderBy('created_at','asc')->limit(1)->first();

    $periodo_original=Carbon::parse($fp->created_at)->clone()->startOfMonth();//->format('Y_m');
    $periodo_actual=Carbon::parse(now())->clone()->endOfMonth();//->format('Y_m');

    $primer_periodo=Carbon::parse($fp->created_at);
    $diff = ($periodo_original->diffInMonths($periodo_actual))+1;
    //$this->info("Diferencia de meses ".$diff);

    $where_anio='';
    $where_mes='';
    $cont_mes=0;



    $clientes=Cliente::whereIn('tipo',['0','1'])
      ->whereIn('id',[
          29.816,
          29.817,
          29.818,
          29.819,
          29.820,
          29.821,
          29.822,
          29.823,
          29.824,
          29.825,
          29.826,
          29.827,
          29.828,
          29.829,
          29.830,
          29.831,
          29.832,
          29.833,
          29.834,
          29.835,
          29.836,
          29.837,
          29.838,
          29.839,
          29.840,
          29.841,
          29.842,
          29.843,
          29.844,
          29.845,
          29.846,
          29.847,
          29.848,
          29.849,
          29.850,
          29.851,
          29.852,
          29.853,
          29.854,
          29.855,
          29.857,
          29.858,
          29.859,
          29.860,
          29.861,
          29.862,
          29.863,
          29.864,
          29.865,
          29.866,
          29.867,
          29.868,
          29.869,
          29.870,
          29.871,
          29.873,
          29.874,
          29.875,
          29.876,
          29.878
      ])
      ->orderBy('id','asc')->get();

    $progress = $this->output->createProgressBar($clientes->count());

      foreach($clientes as $cliente)
      {

          $idcliente=$cliente->id;

          //if($cliente->id==1739)
          {
              $this->warn($cliente->id);
              $delete=SituacionClientes::where('cliente_id',$cliente->id)->delete();

              $periodo_inicial=Carbon::parse($fp->created_at);
              $periodo_ejecucion=null;

              for($i=0;$i<$diff;$i++)
              {
                  $periodo_ejecucion=Carbon::parse($fp->created_at)->addMonths($i);
                  $where_anio=$periodo_ejecucion->format('Y');
                  $where_mes=$periodo_ejecucion->format('m');

                  //contadores
                  $cont_mes=Pedido::where('cliente_id',$cliente->id)->whereYear('created_at',$where_anio)
                      ->whereMonth('created_at',$where_mes)->count();
                  $cont_mes_activo=Pedido::where('cliente_id',$cliente->id)->whereYear('created_at',$where_anio)
                      ->whereMonth('created_at',$where_mes)->activo()->count();
                  $cont_mes_anulado=Pedido::where('cliente_id',$cliente->id)->whereYear('created_at',$where_anio)
                      ->whereMonth('created_at',$where_mes)->activo('0')->count();

                  $situacion_create=SituacionClientes::create([
                      'cliente_id'=>$cliente->id,
                      'situacion'=>'',
                      'cantidad_pedidos'=>$cont_mes,
                      'anulados'=>$cont_mes_anulado,
                      'activos'=>$cont_mes_activo,
                      'periodo'=>Carbon::createFromDate($where_anio, $where_mes)->startOfMonth()->format('Y-m'),
                      'flag_fp'=>'0'
                  ]);

                  $compara=Carbon::parse($fp->created_at);

                  $mes_antes = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth()->subMonth();

                  if($cont_mes==0)
                  {
                      if( $where_anio==$compara->format('Y') && $where_mes==$compara->format('m') )
                      {
                          $situacion_create->update([
                              "situacion" => 'BASE FRIA',
                              "flag_fp" => '0'
                          ]);
                      }
                      else{
                          $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                          switch($situacion_antes->situacion)
                          {
                              case 'BASE FRIA':
                                  $situacion_create->update([
                                      "situacion" => 'BASE FRIA',
                                      "flag_fp" => '0'
                                  ]);
                                  break;

                              case 'RECUPERADO RECIENTE':
                                  $situacion_create->update([
                                      "situacion" => 'RECURRENTE',
                                      "flag_fp" => '1'
                                  ]);
                                  break;

                              case 'RECUPERADO ABANDONO':
                                  $situacion_create->update([
                                      "situacion" => 'RECURRENTE',
                                      "flag_fp" => '1'
                                  ]);
                                  break;

                              case 'NUEVO':
                                  $situacion_create->update([
                                      "situacion" => 'RECURRENTE',
                                      "flag_fp" => '1'
                                  ]);
                                  break;
                              case 'ABANDONO RECIENTE':
                              case 'ABANDONO':
                                  $situacion_create->update([
                                      "situacion" => 'ABANDONO',
                                      "flag_fp" => '1'
                                  ]);
                                  break;
                              case 'RECURRENTE':
                                  if($situacion_antes->activos==0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'ABANDONO RECIENTE',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'RECURRENTE',
                                          "flag_fp" => '1'
                                      ]);
                                  }
                                  break;
                              default:break;
                          }
                      }
                  }
                  else{
                      if( $where_anio==$compara->format('Y') && $where_mes==$compara->format('m') )
                      {
                          $situacion_create->update([
                              "situacion" => 'NUEVO',
                              "flag_fp" => '0'
                          ]);
                      }
                      else{
                          $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();
                          switch($situacion_antes->situacion)
                          {
                              case 'BASE FRIA':
                                  $situacion_create->update([
                                      "situacion" => 'NUEVO',
                                      "flag_fp" => '1'
                                  ]);

                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_antes->flag_fp==0)
                                  {
                                      if($situacion_periodo->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'NUEVO',
                                              "flag_fp" => '1'
                                          ]);
                                      }else{
                                          $situacion_create->update([
                                              "situacion" => 'NUEVO',
                                              "flag_fp" => '0'
                                          ]);
                                      }

                                  }
                                  else if($situacion_antes->flag_fp==1)
                                  {
                                      if($situacion_periodo->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'NUEVO',
                                              "flag_fp" => '1'
                                          ]);
                                      }else{
                                          $situacion_create->update([
                                              "situacion" => 'NUEVO',
                                              "flag_fp" => '0'
                                          ]);
                                      }
                                  }
                                  break;
                              case 'RECUPERADO RECIENTE':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'RECURRENTE',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'RECURRENTE',
                                          "flag_fp" => '1'
                                      ]);
                                  }
                                  break;
                              case 'RECUPERADO ABANDONO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'RECURRENTE',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'RECURRENTE',
                                          "flag_fp" => '1'
                                      ]);
                                  }

                                  break;
                              case 'NUEVO':
                                  $situacion_create->update([
                                      "situacion" => 'RECURRENTE',
                                      "flag_fp" => '1'
                                  ]);

                                  break;
                              case 'ABANDONO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'RECUPERADO ABANDONO',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'ABANDONO',
                                          "flag_fp" => '1'
                                      ]);
                                  }

                                  break;
                              case 'ABANDONO RECIENTE':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'RECUPERADO ABANDONO',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'ABANDONO',
                                          "flag_fp" => '1'
                                      ]);
                                  }

                                  break;
                              case 'RECURRENTE':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      if ($situacion_antes->activos > 0 )
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'RECURRENTE',
                                              "flag_fp" => '1'
                                          ]);
                                      }else{
                                          $situacion_create->update([
                                              "situacion" => 'RECUPERADO RECIENTE',
                                              "flag_fp" => '1'
                                          ]);
                                      }

                                  }else{
                                      if ($situacion_antes->activos > 0 )
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'RECURRENTE',
                                              "flag_fp" => '1'
                                          ]);
                                      }else{
                                          $situacion_create->update([
                                              "situacion" => 'ABANDONO RECIENTE',
                                              "flag_fp" => '1'
                                          ]);
                                      }

                                  }
                                  break;
                              default:
                                  break;
                          }

                      }
                  }

                  if($i==($diff-1))
                  {
                      $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                      $situacion_final=SituacionClientes::where('cliente_id',$cliente->id)
                          ->where('periodo',$mes_actual->format('Y-m'))->first();
                      $cont_ped_activo=Pedido::where('cliente_id',$cliente->id)->activo()->count();
                      $cont_ped_nulo=Pedido::where('cliente_id',$cliente->id)->activo(0)->count();

                      if( ($situacion_final!='BASE FRIA') && ($cont_ped_activo==0) && ($cont_ped_nulo>0) )
                      {
                          $situacion_cambia=SituacionClientes::where('cliente_id',$cliente->id)
                              ->where('periodo',$mes_actual->format('Y-m'))
                              ->first();
                          $situacion_cambia->update([
                              'situacion'=>'NULO'
                          ]);
                      }

                      $situacion_actual=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

                      Cliente::where('id',$cliente->id)->update([
                          'situacion'=>$situacion_actual->situacion
                      ]);

                  }

              }

          }
          $progress->advance();
      }


    $this->info("Finish Cargando ");
    $progress->finish();
    $this->info('FIN');
    //


    //select * from pedidos order by created_at  asc limit 1

    //$date = Carbon::createFromDate(1970,19,12)->age; // 43


    return 0;
  }
}
