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
        foreach($usuarios as $usuario)
        {
          //recorro usuarios
          //solo para el nuevo periodo
          //$meta_create=Meta::where('');

        }


        return 0;
    }
}
