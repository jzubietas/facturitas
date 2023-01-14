<?php

namespace App\Console\Commands;

use App\Models\GrupoPedido;
use Illuminate\Console\Command;

class NormalizeGrupoPedidoOlva extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalize:grupopedido:olva';

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
        $grupopedido = GrupoPedido::query()
            ->where('zona', 'OLVA')
            ->orWhere('direccion', 'like', '%OLVA%')
            ->orWhere('referencia', 'like', '%OLVA%')
            ->orWhere('cliente_recibe', 'like', '%OLVA%')
            ->get();

        $olva = GrupoPedido::createGroupByArray([
            'zona' => 'OLVA'
        ]);

        foreach ($grupopedido as $grupo) {
            \DB::table('grupo_pedido_items')
                ->where('grupo_pedido_id', $grupo->id)
                ->update([
                    'grupo_pedido_id' => $olva->id
                ]);

            $grupo->pedidos()->update([
                'env_zona' => 'OLVA'
            ]);
        }

        foreach ($grupopedido as $grupo) {
            if ($grupo->pedidos()->count() == 0) {
                $grupo->delete();
            }
        }
        return 0;
    }
}
