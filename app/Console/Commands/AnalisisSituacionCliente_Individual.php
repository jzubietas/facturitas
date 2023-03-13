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
     * The name and signature of the console command.
     *
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

      $periodo_original=Carbon::parse($fp->created_at)->startOfMonth();
      $periodo_actual=Carbon::parse(now())->endOfMonth();

      $primer_periodo=Carbon::parse($fp->created_at);
      $diff = ($periodo_original->diffInMonths($periodo_actual))+1;


      $where_anio='';
      $where_mes='';
      $cont_mes=0;



      $clientes=Cliente::whereIn('tipo',['0','1'])->where('id',$cliente_id)->orderBy('id','asc')->get();
      //->where('id',1739) //->where('id',45)
      $progress = $this->output->createProgressBar($clientes->count());
      //$periodo_original=$primer_periodo;

      //$this->info($diff);
      //return 0;

      foreach($clientes as $cliente)
      {

        $idcliente=$cliente->id;

        //if($cliente->id==1739)
        {
          $this->warn($cliente->id);
          $delete=SituacionClientes::where('cliente_id',$cliente->id)->delete();
          //$this->info("situacion en clientes ");

          $periodo_inicial=Carbon::parse($fp->created_at);
          //$periodo_ejecucion=$periodo_inicial;
          $periodo_ejecucion=null;

          for($i=0;$i<$diff;$i++)
          {
            //->info("suma meses : ".$i." a ".$periodo_inicial);
            //$this->info('recorrido for es '.$i);
            //$this->info('inicia en '.Carbon::parse($fp->created_at)->format('Y-m'));
            $periodo_ejecucion=Carbon::parse($fp->created_at)->addMonths($i);
            //$this->info('el hacer for cambia a '.$periodo_ejecucion->format('Y-m')  );

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

            //$this->info('periodo '.Carbon::createFromDate($where_anio, $where_mes)->startOfMonth()->format('Y-m'));

            $compara=Carbon::parse($fp->created_at);

            $mes_antes = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth()->subMonth();

            if($cont_mes==0)
            {
              //primer periodo del sistema
              if( $where_anio==$compara->format('Y') && $where_mes==$compara->format('m') )
              {
                //primer mes y contador 0
                //$this->warn("es igual al primer periodo -".$cont_mes.' - SERA BASE FRIA ');
                $situacion_create->update([
                  "situacion" => 'BASE FRIA',
                  "flag_fp" => '0'
                ]);
              }
              else{
                //$this->warn('Mes antes '.$mes_antes->format('Y-m').' cliente '.$idcliente);
                $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();
                //$this->warn($situacion_antes);

                //$this->info('Mes periodo '.$mes_actual->format('Y-m').' cliente '.$idcliente);
                //$situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                //$this->info($situacion_periodo);

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
                    //$this->warn('Situacion anterior recuperada');
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
              //contador mes > 0    1
              if( $where_anio==$compara->format('Y') && $where_mes==$compara->format('m') )
              {
                //primer mes y contador >0
                //$this->warn("es igual al primer periodo -".$cont_mes.' - SERA NUEVO ');
                $situacion_create->update([
                  "situacion" => 'NUEVO',
                  "flag_fp" => '0'
                ]);
              }
              else{
                //contador mes=1  y no es el primer periodo de primer pedido
                //$this->warn('periodo antes ');
                $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();
                switch($situacion_antes->situacion)
                {
                  case 'BASE FRIA':
                    $situacion_create->update([
                      "situacion" => 'NUEVO',
                      "flag_fp" => '1'
                    ]);

                    $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();//diciembre 2022

                    $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

                    $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                    if($situacion_antes->flag_fp==0)
                    {
                      //flag antes 0  pasa a cliente  test
                      if($situacion_periodo->activos>0)
                      {
                        //actual activos >0
                        $situacion_create->update([
                          "situacion" => 'NUEVO',
                          "flag_fp" => '1'
                        ]);
                      }else{
                        //actual activos 0
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
                        //actual activos >0
                        $situacion_create->update([
                          "situacion" => 'NUEVO',
                          "flag_fp" => '1'
                        ]);
                      }else{
                        //actual activos 0
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


                    //$this->info('SITUACION ANTES RECUPERADO RECIENTE');

                    if($situacion_periodo->activos>0)
                    {
                      //actual activos >0
                      $situacion_create->update([
                        "situacion" => 'RECURRENTE',
                        "flag_fp" => '1'
                      ]);
                    }else{
                      //actual activos 0
                      $situacion_create->update([
                        "situacion" => 'RECURRENTE',
                        "flag_fp" => '1'
                      ]);
                    }

                    /*$situacion_create->update([
                      "situacion" => 'RECURRENTE',
                      "flag_fp" => '1'
                    ]);*/
                    break;
                  case 'RECUPERADO ABANDONO':
                    $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                    $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

                    $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                    //$this->info('SITUACION ANTES RECUPERADO ABANDONO');

                    if($situacion_periodo->activos>0)
                    {
                      //actual activos >0
                      $situacion_create->update([
                        "situacion" => 'RECURRENTE',
                        "flag_fp" => '1'
                      ]);
                    }else{
                      //actual activos 0
                      $situacion_create->update([
                        "situacion" => 'RECURRENTE',
                        "flag_fp" => '1'
                      ]);
                    }

                    /*$situacion_create->update([
                      "situacion" => 'RECURRENTE',
                      "flag_fp" => '1'
                    ]);*/
                    break;
                  case 'NUEVO':
                    $situacion_create->update([
                      "situacion" => 'RECURRENTE',
                      "flag_fp" => '1'
                    ]);

                    //$mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();//diciembre 2022

                    //$situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

                    //$situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                    /*if($situacion_antes->flag_fp==0)
                    {
                      if($situacion_periodo->activos>0)
                      {
                        //actual activos >0
                        $situacion_create->update([
                          "situacion" => 'NUEVO',
                          "flag_fp" => '1'
                        ]);
                      }else{
                        //actual activos 0
                        $situacion_create->update([
                          "situacion" => 'NUEVO',
                          "flag_fp" => '0'
                        ]);
                      }

                    }*/
                    /*else if($situacion_antes->flag_fp==1)
                    {
                      if($situacion_periodo->activos>0)
                      {
                        //actual activos >0
                        $situacion_create->update([
                          "situacion" => 'NUEVO',
                          "flag_fp" => '1'
                        ]);
                      }else{
                        //actual activos 0
                        $situacion_create->update([
                          "situacion" => 'NUEVO',
                          "flag_fp" => '0'
                        ]);
                      }
                    }*/
                    break;
                  case 'ABANDONO':
                    $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                    $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                    $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                    //$this->info('SITUACION ANTES NUEVO');

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

                    /*$situacion_create->update([
                      "situacion" => 'RECUPERADO ABANDONO',
                      "flag_fp" => '1'
                    ]);*/
                    break;
                  case 'ABANDONO RECIENTE':
                    $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                    $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                    $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                    //$this->info('SITUACION ANTES ABANDONO RECIENTE');

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

                    /*$situacion_create->update([
                      "situacion" => 'RECUPERADO ABANDONO',
                      "flag_fp" => '1'
                    ]);*/

                    break;
                  case 'RECURRENTE':
                    $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                    $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                    $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();

                    //$this->info('SITUACION ANTES RECURRENTE');

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

                      /*$situacion_create->update([
                        "situacion" => 'RECUPERADO ABANDONO',
                        "flag_fp" => '1'
                      ]);*/
                    }else{
                      //actual activos 0
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
                    //$this->info('SITUACION ANTES DEFAULT');
                    break;
                }

              }
            }
            //$this->warn('i '.$i);
            //$this->warn('diff '.$diff);

            if($i==($diff-1))
            {
              $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
              //$this->warn('actual '.$mes_actual->format('Y-m'));
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
              //$this->warn($situacion_actual->situacion);
              Cliente::where('id',$cliente->id)->update([
                'situacion'=>$situacion_actual->situacion
              ]);
              //Clientes
            }

          }
          //continue;

          //break;
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
