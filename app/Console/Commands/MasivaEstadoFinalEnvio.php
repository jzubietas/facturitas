<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class MasivaEstadoFinalEnvio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'masiva:estadofinal:envio';

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
        $pedidos = Pedido::query()->activo()
            ->where('pedidos.condicion_envio_code', '=', 12)
            ->where('pedidos.condicion_envio', '=', "ENVIO A COURIER - JEFE OPE")
            ->get();
        $count = 0;
        $result = [];
        foreach ($pedidos as $pedido)
        {
            $grupo=DireccionGrupo::createByPedido($pedido->codigo);
            DireccionGrupo::where('id',$grupo->id)->get()
                ->update([
                    'condicion_envio_code' => Pedido::ENTREGADO_CLIENTE_INT,
                    'condicion_envio' => Pedido::ENTREGADO_CLIENTE
                ]);
            $elpedido=Pedido::where('codigo',$pedido->codigo)->first();
            $elpedido->update([
                'direccion_grupo' => $grupo->id,
                'condicion_envio_code' => Pedido::ENTREGADO_CLIENTE_INT,
                'condicion_envio' => Pedido::ENTREGADO_CLIENTE
            ]);

        }
        
        return 0;
    }
}
