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

    $periodo_original=Carbon::parse($fp->created_at);//->format('Y_m');
    $periodo_actual=Carbon::parse(now());//->format('Y_m');

    $primer_periodo=Carbon::parse($fp->created_at);
    $diff = ($periodo_original->diffInMonths($periodo_actual))+2;
    //$this->info("Diferencia de meses ".$diff);

    $where_anio='';
    $where_mes='';
    $cont_mes=0;



    $clientes=Cliente::whereIn('tipo',['0','1'])
      ->whereIn('id',[
        '3',
        '25',
        '35',
        '36',
        '40',
        '780',
        '783',
        '970',
        '985',
        '990',
        '1207',
        '1260',
        '1298',
        '1300',
        '1310',
        '1444',
        '1477',
        '1481',
        '1490',
        '1581',
        '1675',
        '1676',
        '1699',
        '1870',
        '1879',
        '1899',
        '1902',
        '1903',
        '1993',
        '2241',
        '2275',
        '2431',
        '2453',
        '2500',
        '2683',
        '2744',
        '2817',
        '2829',
        '2942',
        '3209',
        '3252',
        '3418',
        '3419',
        '3672',
        '3714',
        '3755',
        '3773',
        '3920',
        '4031',
        '4133',
        '4207',
        '4210',
        '4228',
        '4334',
        '4422',
        '4424',
        '4427',
        '4440',
        '4444',
        '4450',
        '4455',
        '4458',
        '4459',
        '4463',
        '4465',
        '4467',
        '4469',
        '4474',
        '4475',
        '4477',
        '4478',
        '4490',
        '4492',
        '4494',
        '4496',
        '4532',
        '4538',
        '4541',
        '4543',
        '4561',
        '4562',
        '4564',
        '4570',
        '4580',
        '4582',
        '4585',
        '4586',
        '4589',
        '4594',
        '4597',
        '4606',
        '4607',
        '4608',
        '4622',
        '4625',
        '4631',
        '4636',
        '4640',
        '4659',
        '4677',
        '4708',
        '4759',
        '4763',
        '4792',
        '5187',
        '5206',
        '5222',
        '5224',
        '5233',
        '5238',
        '5242',
        '5245',
        '5257',
        '5261',
        '5271',
        '5276',
        '5277',
        '5290',
        '5299',
        '5323',
        '5331',
        '5333',
        '5341',
        '5353',
        '5361',
        '5384',
        '5399',
        '5414',
        '5417',
        '5458',
        '5459',
        '5466',
        '5508',
        '5518',
        '5542',
        '5558',
        '5622',
        '5691',
        '5716',
        '5778',
        '5779',
        '5791',
        '5823',
        '5849',
        '5864',
        '5976',
        '6014',
        '6202',
        '6306',
        '6360',
        '6603',
        '6690',
        '6725',
        '6748',
        '6872',
        '9944',
        '9955',
        '13032',
        '13034',
        '13039',
        '13051',
        '13052',
        '13053',
        '13057',
        '13058',
        '13059',
        '13063',
        '13078',
        '13083',
        '13088',
        '13089',
        '13091',
        '13092',
        '13093',
        '13101',
        '13104',
        '13154',
        '13278',
        '13372',
        '13383',
        '13408',
        '13426',
        '13455',
        '13464',
        '13467',
        '13553',
        '13632',
        '13649',
        '13653',
        '13705',
        '13760',
        '13799',
        '13862',
        '13875',
        '13888',
        '13946',
        '13959',
        '14016',
        '14079',
        '14106',
        '14107',
        '14122',
        '14148',
        '14272',
        '14317',
        '14359',
        '14390',
        '14441',
        '14499',
        '14501',
        '14522',
        '14585',
        '14648',
        '14691',
        '14713',
        '14767',
        '14808',
        '14841',
        '14934',
        '14938',
        '15165',
        '15202',
        '15208',
        '15236',
        '15297',
        '15374',
        '15397',
        '15646',
        '15653',
        '15719',
        '15739',
        '15826',
        '15889',
        '15924',
        '15930',
        '16015',
        '16042',
        '16110',
        '16408',
        '16470',
        '16580',
        '16587',
        '16599',
        '16664',
        '16677',
        '16969',
        '17065',
        '17091',
        '17207',
        '17288',
        '17507',
        '17598',
        '17943',
        '17987',
        '17996',
        '18079',
        '18240',
        '18265',
        '18478',
        '18486',
        '18593',
        '18665',
        '18670',
        '18674',
        '18700',
        '18840',
        '18860',
        '19085',
        '19099',
        '19478',
        '19607',
        '19781',
        '19807',
        '19858',
        '21000',
        '21086',
        '21267',
        '21271',
        '21272',
        '21273',
        '21284',
        '21294',
        '21295',
        '21341',
        '21374',
        '21478',
        '21558',
        '21600',
        '21631',
        '21759',
        '21911',
        '22184',
        '22336',
        '22552',
        '22580',
        '22662',
        '24031',
        '24032',
        '24033',
        '24038',
        '24048',
        '24050',
        '24056',
        '24058',
        '24064',
        '24068',
        '24075',
        '24078',
        '24081',
        '24083',
        '24085',
        '24090',
        '24108',
        '24109',
        '24111',
        '24113',
        '24116',
        '24118',
        '24119',
        '24128',
        '24141',
        '24164',
        '24172',
        '24176',
        '24178',
        '24180',
        '24181',
        '24182',
        '24188',
        '24190',
        '24192',
        '24194',
        '24206',
        '24213',
        '24215',
        '24217',
        '24219',
        '24225',
        '24228',
        '24239',
        '24241',
        '24242',
        '24243',
        '24245',
        '24300',
        '24329',
        '24334',
        '24448',
        '24489',
        '24491',
        '24495',
        '24498',
        '24555',
        '24603',
        '24648',
        '24673',
        '24699',
        '24740',
        '24764',
        '24773',
        '24783',
        '24786',
        '24807',
        '24827',
        '24830',
        '24836',
        '24972',
        '25054',
        '25085',
        '25088',
        '25093',
        '25111',
        '25116',
        '25125',
        '25207',
        '25213',
        '25246',
        '25316',
        '25325',
        '25326',
        '25327',
        '25328',
        '25331',
        '25345',
        '25359',
        '25364',
        '25368',
        '25371',
        '25397',
        '25405',
        '25432',
        '25443',
        '25460',
        '25486',
        '25491',
        '25505',
        '25527',
        '25563',
        '25587',
        '25595',
        '25638',
        '25641',
        '25684',
        '25710',
        '25714',
        '25717',
        '25720',
        '25722',
        '25723',
        '25726',
        '25730',
        '25740',
        '25744',
        '25766',
        '25772',
        '25780',
        '25791',
        '25795',
        '25814',
        '25844',
        '25877',
        '25882',
        '25941',
        '26001',
        '26018',
        '26125',
        '26168',
        '26174',
        '26184',
        '26190',
        '26204',
        '26229',
        '26358',
        '26368',
        '26373',
        '26413',
        '26461',
        '26462',
        '26482',
        '26574',
        '26603',
        '26604',
        '26615',
        '26684',
        '27005',
        '27008',
        '27145',
        '27186',
        '27278',
        '27303',
        '27491',
        '27542',
        '27627',
        '27661',
        '27683',
        '28000',
        '28129',
        '28340',
        '28913',
        '28924',
        '29182'
      ])
      ->orderBy('id','asc')->get();
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
