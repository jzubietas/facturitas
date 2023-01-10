<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\GrupoPedido;
use App\Models\Pedido;
use App\Models\PedidoMotorizadoHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CambiarEstadoSobreNoEnviado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estado:migrar_con_direccion';

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
        $grupos = DireccionGrupo::join('clientes as c', 'c.id', 'direccion_grupos.cliente_id')
            ->join('users as u', 'u.id', 'c.user_id')
            ->select([
                'direccion_grupos.*',
                'u.identificador',
                DB::raw(" (select 'LIMA') as destino "),
                DB::raw('(select DATE_FORMAT( direccion_grupos.created_at, "%Y-%m-%d")   from direccion_grupos dpa where dpa.id=direccion_grupos.id) as fecha'),
            ])
            ->where('direccion_grupos.estado', '1')
            ->where('direccion_grupos.condicion_envio_code', Pedido::MOTORIZADO_INT)
            ->where('direccion_grupos.motorizado_status', Pedido::ESTADO_MOTORIZADO_NO_CONTESTO)
            ->get();

        foreach ($grupos as $grupo) {
            $this->warn('Pedido por enviar a con direccion: '.$grupo->id);
            $grupoPedido = GrupoPedido::query()->create([
                'zona' => $grupo->distribucion,
                'provincia' => $grupo->destino,
                'distrito' => $grupo->distrito,
                'direccion' => $grupo->direccion,
                'referencia' => $grupo->referencia,
                'cliente_recibe' => $grupo->nombre_cliente,
                'telefono' => $grupo->celular,
            ]);
            $grupoPedido->pedidos()->attach($grupo->pedidos()->select('pedidos.*', 'detalle_pedidos.nombre_empresa')
                ->join('detalle_pedidos', 'pedidos.id', '=', 'detalle_pedidos.pedido_id')
                ->where('pedidos.estado', '=', '1')
                ->where('detalle_pedidos.estado', '=', '1')
                ->get()
                ->mapWithKeys(fn($pedido) => [
                    $pedido->id => [
                        'razon_social' => $pedido->nombre_empresa,
                        'codigo' => $pedido->codigo,
                    ]
                ]));
            $grupo->pedidos()->where('pedidos.estado', '=', '1')->update([
                'condicion_envio' => Pedido::RECEPCION_COURIER,
                'condicion_envio_code' => Pedido::RECEPCION_COURIER_INT,
            ]);

            $grupo->update([
                'estado' => 0
            ]);

            PedidoMotorizadoHistory::query()
                ->where([
                    'direccion_grupo_id' => $grupo->id,
                    'status' => '2',
                ])
                ->update([
                    'pedido_grupo_id' => $grupoPedido->id,
                ]);
            $this->info('[Success] Pedido por enviar a con direccion: '.$grupo->id);
        }
        return 0;
    }
}
