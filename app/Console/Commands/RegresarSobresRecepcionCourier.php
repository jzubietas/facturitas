<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\GrupoPedido;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class RegresarSobresRecepcionCourier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regresar:recepcion_courier {--codigos=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description ';

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
        $codigos = $this->option('codigos');
        $table=[];
        $codigosarray=[];
        if (!empty($codigos)) {
            $codigosarray = collect(explode(',', $codigos))->filter()->trim()->all();
            $pedidos = Pedido::query()->with('direcciongrupo')->activo()->whereIn('codigo', $codigosarray)->get();
            foreach ($pedidos as $pedido) {

                if ($pedido->direcciongrupo != null) {
                    $pedido->update([
                        'direccion_grupo' => null
                    ]);
                    DireccionGrupo::restructurarCodigos($pedido->direcciongrupo);
                }

                $pedido->update([
                    'fecha_recepcion_courier' => Carbon::now(),
                    'condicion_envio' => Pedido::RECEPCION_COURIER,
                    'condicion_envio_code' => Pedido::RECEPCION_COURIER_INT,
                    'condicion_envio_at' => now(),
                ]);
                if ($pedido->estado_sobre) {
                    GrupoPedido::createGroupByPedido($pedido,false,true);
                }
                $table[]=[
                    $pedido->codigo,
                    ($pedido->direcciongrupo != null?'yes':'no'),
                    $pedido->estado_sobre,
                    $pedido->condicion_envio,
                ];
            }
        }
        $this->table(['codigos'],collect($codigosarray)->map(fn($c)=>[$c]));
        $this->table(['codigo','grupo','con direccion','condicion'],$table);
        return 0;
    }
}
