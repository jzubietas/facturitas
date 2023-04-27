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

      $periodo_original=Carbon::parse($fp->created_at)->clone()->startOfMonth();
      $periodo_actual=Carbon::parse(now())->clone()->endOfMonth();

    $primer_periodo=Carbon::parse($fp->created_at);
    $diff = ($periodo_original->diffInMonths($periodo_actual))+1;
    //$this->info("Diferencia de meses ".$diff);

    $where_anio='';
    $where_mes='';
    $cont_mes=0;



    $clientes=Cliente:://join('clientes_b18 as a','a.celular','clientes.celular')->whereIn('clientes.tipo',['0','1'])
      //where('situacion','=','RECUPERADO RECIENTE')
        whereIn('clientes.id',[915,958,1045,1094,1121,1127,1156,1163,1199,1217,1232,1249,1261,1273,1287,1291,1318,1326,1328,1335,1358,1693,1701,1791,1841,1892,1898,1904,1909,1921,1943,2047,2109,2111,2141,2431,2626,2658,2664,2669,2697,2751,2791,2815,3002,3258,3401,3452,3624,3804,4248,4264,4265,4359,4407,4497,4737,4766,4788,5044,5065,5156,5178,5188,5195,5205,5213,5223,5230,5232,5240,5258,5260,5278,5283,5324,5334,5347,5373,5375,5378,5396,5397,5434,5438,5444,5455,5476,5483,5522,5524,5626,5719,5919,5946,5998,6004,6103,6109,6112,6789,10003,13050,13626,13739,13766,14045,14052,14341,14576,14654,14671,15365,15486,15740,15994,15995,16209,16220,16503,16596,16679,16804,16846,17258,17262,17404,17435,17440,18300,18547,18745,18933,19138,19753,19966,21031,21218,21233,21268,21275,21290,21291,21297,21309,21358,21373,21537,21586,21974,21999,22030,22140,22333,22338,22350,22378,22383,22516,22527,22572,22594,22615,24066,24074,24079,24086,24088,24089,24093,24095,24173,24201,24465,24487,24556,24558,24618,24669,24680,24706,24723,24802,24824,24885,24895,24971,25032,25224,25238,25247,25254,25271,25358,25421,25678,25733,25815,25997,26018,26168,26427,26508,26577,26628,26652,26850,26898,26963,27207,27502,27536,27708,27743,27827,27903,28040,28045,28056,28058,28155,28157,28225,28329,28482,28680,28736,28858,28926,29042,29103,29190,29260,29270,29285,29421,29498,29529,29691,29879,29997,30119,30201,30215,30238,30516,30545,30563,30572,30860,31021,31292,31302,31349,31418,31784,32006,32070,32367,32453,32464,32521,32546,32567,32717,32739,32771,32830,33058,33120,33187,33278,33304,33311,33326,33380,33420,33422,33425,33447,33461,33510,33513,33514,33515,33516,33517,33518,33519,33520,33521,33522,33523,33524,33525,33526,33527,33528,33529,33530,33531,33532,33533,33534,33535,33536,33537,33538,33539,33540,33541,33542,33543,33544,33545,33546,33547,33548,33549,33550,33551,33552,33553,33554,33555,33556,33557,33558,33559,33560,33561,33562,33563,33564,33565,33566,33567,33568,33569,33570,33571,33572,33573,33574,33575,33576,33577,33578,33579,33580,33581,33582,33583,33584,33585,33586,33587,33588,33589,33590,33591,33592,33593,33594,33595,33596,33597,33598,33599,33600,33601,33602,33603,33604,33605,33606,33607])//->where('clientes.tipo',1)
      ->orderBy('clientes.id','asc')->get();

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
                      ->whereMonth('created_at',$where_mes)->where('codigo', 'not like', "%-C%")->count();
                  $cont_mes_activo=Pedido::where('cliente_id',$cliente->id)->whereYear('created_at',$where_anio)
                      ->whereMonth('created_at',$where_mes)->activo()->where('codigo', 'not like', "%-C%")->count();
                  $cont_mes_anulado=Pedido::where('cliente_id',$cliente->id)->whereYear('created_at',$where_anio)
                      ->whereMonth('created_at',$where_mes)->activo('0')->where('codigo', 'not like', "%-C%")->count();

                  //$this->warn('cont_mes '.$cont_mes.' where_anio '.$where_anio.' where_mes '.$where_mes);

                  $situacion_create=SituacionClientes::create([
                      'cliente_id'=>$cliente->id,
                      'user_id'=>$cliente->user_id,
                      'user_identificador'=>$cliente->user->identificador,
                      'user_clavepedido'=>$cliente->user->clave_pedidos,
                      'situacion'=>'',
                      'cantidad_pedidos'=>$cont_mes,
                      'anulados'=>$cont_mes_anulado,
                      'activos'=>$cont_mes_activo,
                      'periodo'=>Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->format('Y-m'),
                      'flag_fp'=>'0'
                  ]);

                  $compara=Carbon::parse($fp->created_at);

                  $mes_antes = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth();

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
                                      "situacion" => 'CAIDO',
                                      "flag_fp" => '1'
                                  ]);
                                  break;

                              case 'RECUPERADO ABANDONO':
                                  $situacion_create->update([
                                      "situacion" => 'CAIDO',
                                      "flag_fp" => '1'
                                  ]);
                                  break;

                              case 'NUEVO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      if($situacion_antes->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'LEVANTADO',"flag_fp" => '1'
                                          ]);
                                      }else{
                                          $situacion_create->update([
                                              "situacion" => 'NUEVO',"flag_fp" => '1'//
                                          ]);
                                      }
                                  }else{
                                      if($situacion_antes->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'CAIDO',"flag_fp" => '1'
                                          ]);
                                      }else{
                                          $situacion_create->update([
                                              "situacion" => 'NULO',"flag_fp" => '1'
                                          ]);
                                      }

                                  }
                                  break;
                              case 'NULO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  $mes_antes = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth();
                                  $mes_antes_2 = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth(2);
                                  $mes_antes_3 = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth(3);

                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();
                                  $situacion_antes_2=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes_2->format('Y-m'))->first();
                                  $situacion_antes_3=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes_3->format('Y-m'))->first();

                                  //marzo estoy sin pedidos totales
                                  if($situacion_periodo->activos==0)
                                  {
                                      if($situacion_antes->activos==0)
                                      {
                                          //es febrero
                                          if($situacion_antes_2->activos==0)
                                          {
                                              //es enero
                                              if($situacion_antes_3->activos==0)
                                              {
                                                  // es diciembre
                                                  //a abandono
                                                  $situacion_create->update([
                                                      "situacion" => 'NULO',"flag_fp" => '1'
                                                  ]);

                                              }else if($situacion_antes_3->activos>0)
                                              {
                                                  //a abandono reciente
                                                  $situacion_create->update([
                                                      "situacion" => 'ABANDONO RECIENTE',"flag_fp" => '1'
                                                  ]);
                                              }
                                          }else if($situacion_antes_2->activos>0)
                                          {
                                              $situacion_create->update([
                                                  "situacion" => 'ABANDONO RECIENTE',"flag_fp" => '1'
                                              ]);
                                          }
                                      }else if($situacion_antes->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'CAIDO',"flag_fp" => '1'
                                          ]);
                                      }
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'LEVANTADO',"flag_fp" => '1'
                                      ]);
                                  }
                                  break;
                              case 'ABANDONO RECIENTE':
                              case 'ABANDONO':
                                  $situacion_create->update([
                                      "situacion" => 'ABANDONO',
                                      "flag_fp" => '1'
                                  ]);
                                  break;
                              case 'CAIDO':
                                  if($situacion_antes->activos==0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'ABANDONO RECIENTE',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'CAIDO',
                                          "flag_fp" => '1'
                                      ]);
                                  }
                                  break;
                              case 'LEVANTADO':
                                  if($situacion_antes->activos==0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'CAIDO',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'CAIDO',
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
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  //$this->warn($mes_actual);
                                  $mes_antes = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth();
                                  $mes_antes_2 = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth(2);
                                  $mes_antes_3 = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth(3);

                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();


                                  if($situacion_periodo->anulados==0)
                                  {

                                      if($situacion_periodo->activos==0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'BASE FRIA',
                                              "flag_fp" => '1'
                                          ]);
                                      }else if($situacion_periodo->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'NUEVO',
                                              "flag_fp" => '1'
                                          ]);
                                      }
                                  }
                                  else if($situacion_periodo->anulados>0)
                                  {

                                      if($situacion_periodo->activos==0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'NULO',
                                              "flag_fp" => '1'
                                          ]);
                                      }else if($situacion_periodo->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'NUEVO',
                                              "flag_fp" => '1'
                                          ]);
                                      }
                                  }


                                  break;
                              case 'RECUPERADO RECIENTE':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'LEVANTADO',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'LEVANTADO',
                                          "flag_fp" => '1'
                                      ]);
                                  }
                                  break;
                              case 'RECUPERADO ABANDONO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      $situacion_create->update([
                                          "situacion" => 'LEVANTADO',
                                          "flag_fp" => '1'
                                      ]);
                                  }else{
                                      $situacion_create->update([
                                          "situacion" => 'CAIDO',
                                          "flag_fp" => '1'
                                      ]);
                                  }

                                  break;
                              case 'NUEVO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();


                                  if($situacion_periodo->activos>0)
                                  {
                                      if($situacion_antes->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'LEVANTADO',"flag_fp" => '1'
                                          ]);
                                      }else{
                                          $situacion_create->update([
                                              "situacion" => 'NUEVO',"flag_fp" => '1'//
                                          ]);
                                      }
                                  }else{
                                      if($situacion_antes->activos>0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'CAIDO',"flag_fp" => '1'
                                          ]);
                                      }else{
                                          $situacion_create->update([
                                              "situacion" => 'NULO',"flag_fp" => '1'
                                          ]);
                                      }

                                  }
                                  break;
                              case 'NULO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  $mes_antes = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth();
                                  $mes_antes_2 = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth(2);
                                  $mes_antes_3 = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth(3);

                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();
                                  $situacion_antes_2=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes_2->format('Y-m'))->first();
                                  $situacion_antes_3=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes_3->format('Y-m'))->first();


                                  //marzo estoy sin pedidos totales
                                  if($situacion_periodo->anulados==0)
                                  {
                                      if($situacion_periodo->activos==0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'NULO',"flag_fp" => '1'
                                          ]);
                                      }else if($situacion_periodo->activos>0)
                                      {
                                          if($situacion_antes->activos==0)
                                          {
                                              if($situacion_antes->flag_fp==1)
                                              {
                                                  if($situacion_antes_2->activos==0)
                                                  {
                                                      if($situacion_antes_2->flag_fp==1)
                                                      {
                                                          if($situacion_antes_3->activos==0)
                                                          {
                                                              if($situacion_antes_3->flag_fp==1)
                                                              {
                                                                  $situacion_create->update([
                                                                      "situacion" => 'RECUPERADO ABANDONO',"flag_fp" => '1'
                                                                  ]);
                                                              }
                                                              else{
                                                                  $situacion_create->update([
                                                                      "situacion" => 'RECUPERADO RECIENTE',"flag_fp" => '1'
                                                                  ]);
                                                              }
                                                          }else if($situacion_antes_3->activos>0)
                                                          {
                                                              //a abandono reciente
                                                              $situacion_create->update([
                                                                  "situacion" => 'RECUPERADO RECIENTE',"flag_fp" => '1'
                                                              ]);
                                                          }
                                                      }
                                                      else{
                                                          $situacion_create->update([
                                                              "situacion" => 'RECUPERADO RECIENTE',"flag_fp" => '1'
                                                          ]);
                                                      }
                                                  }else if($situacion_antes_2->activos>0)
                                                  {
                                                      $situacion_create->update([
                                                          "situacion" => 'NUEVO',"flag_fp" => '1'
                                                      ]);
                                                  }
                                              }else{
                                                  $situacion_create->update([
                                                      "situacion" => 'RECUPERADO RECIENTE',"flag_fp" => '1'
                                                  ]);
                                              }
                                          }
                                          else if($situacion_antes->activos>0)
                                          {
                                              $situacion_create->update([
                                                  "situacion" => 'NUEVO',"flag_fp" => '1'
                                              ]);
                                          }
                                      }
                                  }
                                  else if($situacion_periodo->anulados>0)
                                  {
                                      if($situacion_periodo->activos==0)
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'NULO',"flag_fp" => '1'
                                          ]);

                                      }else if($situacion_periodo->activos>0)
                                      {
                                          if($situacion_antes->activos==0)
                                          {
                                              //es febrero
                                              if($situacion_antes_2->activos==0)
                                              {
                                                  //es enero
                                                  if($situacion_antes_3->activos==0)
                                                  {
                                                      // es diciembre
                                                      //a abandono
                                                      $situacion_create->update([
                                                          "situacion" => 'NULO',"flag_fp" => '1'
                                                      ]);

                                                  }else if($situacion_antes_3->activos>0)
                                                  {
                                                      //a abandono reciente
                                                      $situacion_create->update([
                                                          "situacion" => 'RECUPERADO RECIENTE',"flag_fp" => '1'
                                                      ]);
                                                  }
                                              }else if($situacion_antes_2->activos>0)
                                              {
                                                  $situacion_create->update([
                                                      "situacion" => 'NUEVO',"flag_fp" => '1'
                                                  ]);
                                              }
                                          }else if($situacion_antes->activos>0)
                                          {
                                              $situacion_create->update([
                                                  "situacion" => 'NUEVO',"flag_fp" => '1'
                                              ]);
                                          }
                                      }
                                  }
                                  break;
                              case 'ABANDONO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
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
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
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
                              case 'CAIDO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      if ($situacion_antes->activos > 0 )
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'LEVANTADO',
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
                                              "situacion" => 'LEVANTADO',
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
                              case 'LEVANTADO':
                                  $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                                  $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                                  $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                                  if($situacion_periodo->activos>0)
                                  {
                                      if ($situacion_antes->activos > 0 )
                                      {
                                          $situacion_create->update([
                                              "situacion" => 'LEVANTADO',
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
                                              "situacion" => 'CAIDO',
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
                      $mes_actual = Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth();
                      $situacion_final=SituacionClientes::where('cliente_id',$cliente->id)
                          ->where('periodo',$mes_actual->format('Y-m'))->first();
                      $cont_ped_activo=Pedido::where('cliente_id',$cliente->id)->activo()->count();
                      $cont_ped_nulo=Pedido::where('cliente_id',$cliente->id)->activo(0)->count();

                      /*if( ($situacion_final!='BASE FRIA') && ($cont_ped_activo==0) && ($cont_ped_nulo>0) )
                      {
                          $situacion_cambia=SituacionClientes::where('cliente_id',$cliente->id)
                              ->where('periodo',$mes_actual->format('Y-m'))
                              ->first();
                          $situacion_cambia->update([
                              'situacion'=>'NULO'
                          ]);
                      }*/
                      if($cliente->estado==1)
                      {
                          if( ($situacion_final->situacion=='BASE FRIA') && $cliente->tipo==1 )
                          {
                              $situacion_cambia=SituacionClientes::where('cliente_id',$cliente->id)
                                  ->where('periodo',$mes_actual->format('Y-m'))
                                  ->update([
                                      'situacion'=>'PRETENDIDO'
                                  ]);
                          }
                      }else{
                          $situacion_cambia=SituacionClientes::where('cliente_id',$cliente->id)
                              ->where('periodo',$mes_actual->format('Y-m'))
                              ->update([
                                  'situacion'=>'BLOQUEADO'
                              ]);
                      }

                      $situacion_actual=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

                      Cliente::where('id',$cliente->id)->update([
                          'situacion'=>$situacion_actual->situacion
                      ]);

                  }

              }

          }

          $final_cliente=Cliente::where('id',$cliente->id)->first();
          $this->info("situacion final es ".$final_cliente->situacion);
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
