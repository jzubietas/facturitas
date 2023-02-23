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

      $periodo_original=Carbon::parse($fp->created_at);//->format('Y_m');
      $periodo_actual=Carbon::parse(now());//->format('Y_m');

      $primer_periodo=Carbon::parse($fp->created_at);
      $diff = ($periodo_original->diffInMonths($periodo_actual))+1;
      //$this->info("Diferencia de meses ".$diff);

      $where_anio='';
      $where_mes='';
      $cont_mes=0;



      $clientes=Cliente::whereIn('tipo',['0','1'])->where('id',$cliente_id)->orderBy('id','asc')->get();
      //->where('id',1739) //->where('id',45)
      $progress = $this->output->createProgressBar($clientes->count());
      //$periodo_original=$primer_periodo;

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
            $periodo_ejecucion=Carbon::parse($fp->created_at)->addMonths($i);

            //$this->warn("periodo ejecucion: ".$periodo_ejecucion);

            $where_anio=$periodo_ejecucion->format('Y');
            $where_mes=$periodo_ejecucion->format('m');

            //$this->info("where  ".$where_anio.' '.$where_mes);|

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
            $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
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
                //$this->warn('Situacion en '.$mes_antes->format('Y-m').' fue '.$situacion_antes);
                $situacion_periodo=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();

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

                  case 'RECUPERADO ABANDONO': $this->warn('Situacion anterior recuperada');
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
                //primer mes y contador >0
                //$this->warn("es igual al primer periodo -".$cont_mes.' - SERA NUEVO ');
                $situacion_create->update([
                  "situacion" => 'NUEVO',
                  "flag_fp" => '0'
                ]);

              }

              else{
                $this->warn('año: '.$where_anio);
                $this->warn('mes: '.$where_mes);
                //$this->warn('Mes antes '.$mes_antes->format('Y-m'));
                $situacion_antes=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();
                $mes_actual = Carbon::createFromDate($where_anio, $where_mes)->startOfMonth();
                $this->warn('MES ACTUAL -> '.$mes_actual->format('Y-m'). $cliente_id);
                $situacion_actual=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_actual->format('Y-m'))->first();
                //$this->warn('Situacion en '.$mes_antes->format('Y-m').' fue '.$situacion_antes);
                $this->warn('MES ANTES -> ' .$mes_antes);
                $this->info('SITUACION DEL MES ANTERIOR -> '.$situacion_antes->situacion);
                $this->info('SITUACION ACTUAL -> '.$situacion_actual->situacion);
                switch($situacion_antes->situacion)
                {
                  case 'BASE FRIA':
                    $situacion_create->update([
                      "situacion" => 'NUEVO',
                      "flag_fp" => '0'
                    ]);
                    break;
                  case 'RECUPERADO RECIENTE':
                    $situacion_create->update([
                      "situacion" => 'RECURRENTE',
                      "flag_fp" => '1'
                    ]);
                    break;
                  case 'RECUPERADO ABANDONO': $this->warn('MENSAJE RECUPERADO');
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
                  case 'ABANDONO':
                    $situacion_create->update([
                      "situacion" => 'RECUPERADO ABANDONO',
                      "flag_fp" => '1'
                    ]);
                    break;
                  case 'ABANDONO RECIENTE':

                    $situacion_create->update([
                      "situacion" => 'RECUPERADO ABANDONO',
                      "flag_fp" => '1'
                    ]);
                    if ($situacion_periodo->cantidad_pedidos > 0 && $situacion_periodo->activos == 0 ) {
                      $situacion_antes_recuperado_abandono=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();
                      if ($situacion_antes_recuperado_abandono->situacion == 'ABANDONO RECIENTE' && $situacion_antes_recuperado_abandono->activos == 0){
                        $situacion_create->update([
                          "situacion" => 'ABANDONO',
                        ]);
                      }
                    }
                    break;
                  case 'RECURRENTE':
                    $this->warn('aquiiiiii: '.$situacion_antes->activos);
                    if($situacion_antes->activos==0)
                    {
                      if ($situacion_periodo->cantidad_pedidos > 0 && $situacion_periodo->activos == 0 ) {
                        $situacion_antes_recuperado_abandono=SituacionClientes::where('cliente_id',$cliente->id)->where('periodo',$mes_antes->format('Y-m'))->first();
                        if ($situacion_antes_recuperado_abandono->situacion == 'RECURRENTE' && $situacion_antes_recuperado_abandono->activos == 0){
                          $situacion_create->update([
                            "situacion" => 'ABANDONO RECIENTE',
                          ]);
                        }
                      }

                    }else{
                      $this->warn('aquiiiiii: '.$situacion_antes->activos);
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
            //$this->warn('i '.$i);
            //$this->warn('diff '.$diff);
            if($i==($diff-1))
            {
              //$this->warn('ultimo mes ');
              //update clientes

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
