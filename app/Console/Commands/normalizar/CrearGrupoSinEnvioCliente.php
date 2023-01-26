<?php

namespace App\Console\Commands\normalizar;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class CrearGrupoSinEnvioCliente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'creargrupo:sin_envio_cliente';

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
        $pedidos = Pedido::activo()->with('direcciongrupo')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('condicion_envio', Pedido::ENTREGADO_SIN_ENVIO_CLIENTE)
                        ->orWhere('condicion_envio_code', Pedido::ENTREGADO_SIN_ENVIO_CLIENTE_INT);
                });
                $query->orWhere(function ($query) {
                    $query->where('condicion_envio', Pedido::ENTREGADO_SIN_SOBRE_CLIENTE)
                        ->orWhere('condicion_envio_code', Pedido::ENTREGADO_SIN_SOBRE_CLIENTE_INT);
                });
                $query->orWhere('condicion_envio_code', Pedido::ENTREGADO_CLIENTE_INT);
            })
            ->get();
        $this->error("Cantidad: " . $pedidos->count());
        foreach ($pedidos as $pedido) {
            $code = array_flip(Pedido::$estadosCondicionEnvioCode)[$pedido->condicion_envio];
            if($code!=$pedido->condicion_envio_code){
                $this->error("Pedido estado diferente: " . $pedido->codigo);
                $pedido->update([
                    'condicion_envio' => $pedido->condicion_envio,
                    'condicion_envio_code' => $code,
                    'condicion_envio_at' => $pedido->condicion_envio_at,
                ]);
            }
            if ($pedido->direcciongrupo == null) {
                $this->warn($pedido->codigo.' - '.$pedido->condicion_envio);
                DireccionGrupo::createByPedido($pedido);
            } else {
                if($pedido->condicion_envio_code!=Pedido::ENTREGADO_CLIENTE_INT) {
                    $this->info($pedido->codigo);
                    if($code!=$pedido->direcciongrupo->condicion_envio_code) {
                        $pedido->direcciongrupo->update([
                            'condicion_envio' => $pedido->condicion_envio,
                            'condicion_envio_code' => $code,
                            'condicion_envio_at' => $pedido->condicion_envio_at,
                        ]);
                    }
                }
            }
        }
        return 0;
    }
}
