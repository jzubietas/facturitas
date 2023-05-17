<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\SituacionClientes;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AnalisisSituacionCliente_Individual extends Command
{
    /**
     * The name and signature of the console comman
     * @var string
     */
    protected $signature = 'show:analisis:situacion_individual {celular}';

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
      //$cliente_id=$this->argument('celular');
      $cliente_id=Cliente::where('celular',$this->argument('celular'))->first()->id;
      //$this->warn("Cargando primer pedido mes anio");
      $fp=Pedido::orderBy('created_at','asc')->limit(1)->first();

        $fp->created_at=Carbon::parse($fp->created_at)->startOfMonth();

      $periodo_original=Carbon::parse($fp->created_at)->clone()->startOfMonth();
      $periodo_actual=Carbon::parse(now())->clone()->endOfMonth();

      $primer_periodo=Carbon::parse($fp->created_at);
      $diff = ($periodo_original->diffInMonths($periodo_actual))+1;


      $where_anio='';
      $where_mes='';
      $cont_mes=0;



      $clientes=Cliente::whereIn('tipo',['0','1'])->where('id',$cliente_id)
          ->where('congelado','<>',1)
          ->where('bloqueado','<>',1)
          ->orderBy('id','asc')->get();
      //->where('id',1739) //->where('id',45)
      $progress = $this->output->createProgressBar($clientes->count());
      //$periodo_original=$primer_periodo;

      //$this->info($diff);
      //return 0;

        $periodo_inicial=Carbon::parse($fp->created_at)->clone()->startOfMonth();
        $periodo_ejecucion=null;

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
                        ->where('pendiente_anulacion','<>',1)
                        ->whereMonth('created_at',$where_mes)->where('codigo', 'not like', "%-C%")->count();
                    $cont_mes_activo=Pedido::where('cliente_id',$cliente->id)->whereYear('created_at',$where_anio)
                        ->where('pendiente_anulacion','<>',1)
                        ->whereMonth('created_at',$where_mes)->activo()->where('codigo', 'not like', "%-C%")->count();
                    $cont_mes_anulado=Pedido::where('cliente_id',$cliente->id)->whereYear('created_at',$where_anio)
                        ->where('pendiente_anulacion','<>',1)
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
                                            "situacion" => 'CAIDO',
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
                                                if($situacion_antes->situacion=='NULO')
                                                {
                                                    //verificar si tuvo algun pedido

                                                    //si tuvo un pedido
                                                    $verificar_fecha=Carbon::createFromDate($where_anio, $where_mes,1)->startOfMonth()->subMonth()->endOfMonth()->format('Y-m-d');
                                                    $pedidos_c=Pedido::query()->where('cliente_id',$situacion_periodo->cliente_id)
                                                        ->whereDate('created_at','<=',$verificar_fecha)
                                                        ->where('estado','=',1)
                                                        ->count();

                                                    if($pedidos_c>0)
                                                    {
                                                        if($situacion_antes_2=='NULO')
                                                        {
                                                            $situacion_create->update([
                                                                "situacion" => 'RECUPERADO ABANDONO',"flag_fp" => '1'
                                                            ]);
                                                        }else{
                                                            $situacion_create->update([
                                                                "situacion" => 'RECUPERADO RECIENTE',"flag_fp" => '1'
                                                            ]);
                                                        }
                                                    }else{
                                                        $situacion_create->update([
                                                            "situacion" => 'NUEVO',"flag_fp" => '1'
                                                        ]);
                                                    }
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
                                    'situacion'=>'BLOQUEADO',
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

      return 0;
    }
}
