<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use App\Models\Meta;
use App\Models\Pedido;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutomaticMetasReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automatic:metas:reset';

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

        //crear metas este mes para usuarios

        $usuarios=User::whereIn('rol',[User::ROL_ASESOR])->orderBy('id','asc')->get();

        $where_anio=$periodo_actual->format('Y');
        $where_mes=$periodo_actual->format('m');
        foreach($usuarios as $usuario)
        {
          //recorro usuarios
          //solo para el nuevo periodo

          $meta_create_validar=Meta::where('user_id',$usuario->id)->where('anio',$where_anio)->where('mes',$where_mes)->count();
          if($meta_create_validar>0)
          {
            //
            /*User::where('id',$usuario->id )->where('rol',User::ROL_ASESOR)->update(
              [
                'meta_pedido'=>0,
                'meta_pedido_2'=>0,
                'meta_cobro'=>0,
                'meta_quincena'=>0
              ]
            );*/
          }
          else{
            Meta::create(
              [
                'rol'=>User::ROL_ASESOR,
                'user_id'=>$usuario->id,
                'email'=>$usuario->email,
                'anio'=>$where_anio,
                'mes'=>$where_mes,
                'meta_pedido'=>0,
                'meta_pedido_2'=>0,
                'meta_cobro'=>0,
                'status'=>1,
                'created_at'=>now(),
                'meta_quincena'=>0
              ]
            );
            /*User::where('id',$usuario->id )->where('rol',User::ROL_ASESOR)->update(
              [
                'meta_pedido'=>0,
                'meta_pedido_2'=>0,
                'meta_pedido_cobro'=>0,
                'meta_quincena'=>0
              ]
            );*/

          }

        }


        return 0;
    }
}
