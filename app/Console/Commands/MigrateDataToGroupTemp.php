<?php

namespace App\Console\Commands;

use App\Models\Distrito;
use App\Models\GrupoPedido;
use App\Models\Pedido;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateDataToGroupTemp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:pedidos-distribucion-group';

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
        $pedidos = Pedido::join('clientes as c', 'pedidos.cliente_id', 'c.id')
            ->select([
                'pedidos.*',
                'detalle_pedidos.nombre_empresa'
            ])
            ->join('detalle_pedidos', 'pedidos.id', 'detalle_pedidos.pedido_id')
            ->activo()
            ->where('detalle_pedidos.estado', '1')
            ->whereIn('pedidos.condicion_envio_code', [Pedido::RECEPCION_COURIER_INT])
            ->conDireccionEnvio()
            ->sinZonaAsignadaEnvio()
            ->get();
        $pedidosGroups = $pedidos->map(function ($item) {
            $item->_grupo_part = $item->cliente_id . '_' .($item->env_distrito ?: 'n/a') . '_' . ($item->env_zona ?: 'n/a') . '_' . ($item->env_direccion ?: 'n/a');
            return $item;
        })
            ->groupBy(['_grupo_part'])
            ->values();
        $this->info('Data: ' . count($pedidos));
        foreach ($pedidosGroups as $pedidos) {
            /**
             * @var Pedido $first
             */
            $first = \Arr::first($pedidos);
            $grupoPedido = GrupoPedido::createGroupByPedido($first);
            $grupoPedido->pedidos()->sync(collect($pedidos)->mapWithKeys(fn($p) => [$p->id=>[
                'razon_social'=>$p->nombre_empresa,
                'codigo'=>$p->codigo,
            ]])->all());
        }
        return 0;
    }
}
