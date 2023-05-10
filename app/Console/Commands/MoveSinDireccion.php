<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class MoveSinDireccion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'move:sindireccion {--codes=}';

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
        $codes = $this->option('codes');
        if ($codes) {
            $codes = explode(",", $codes);
            $pedidos = Pedido::query()->with('direcciongrupo')->whereIn('codigo', $codes)->get();
            foreach ($pedidos as $pedido) {
                if($pedido->estado_sobre==1){
                    continue;
                }
                if ($pedido->direcciongrupo != null) {
                    $pedido->update([
                        'direccion_grupo' => null
                    ]);
                    DireccionGrupo::restructurarCodigos($pedido->direcciongrupo);
                }
                $pedido->update([
                    'condicion_envio' => Pedido::RECEPCION_COURIER,
                    'condicion_envio_code' => Pedido::RECEPCION_COURIER_INT,
                    'condicion_envio_at' => now()
                ]);
            }
        }
        return 0;
    }
}
