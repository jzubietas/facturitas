<?php

namespace App\Console\Commands\automatic;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class OlvaMoveTiendaAgente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'olva:move-tienda-agente';

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
        /**
         * 20/01/2023
         *
         */
        $grupos = DireccionGrupo::query()
            ->activo()
            ->whereIn('condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
                Pedido::EN_TIENDA_AGENTE_OLVA_INT,
            ])
            ->whereDate('fecha_salida', '<=', now()->startOfDay()->subDays(20))
            ->get();

        foreach ($grupos as $grupo) {
            DireccionGrupo::cambiarCondicionEnvio($grupo, Pedido::NO_ENTREGADO_OLVA_INT);
            $this->info("Grupo (20): " . optional($grupo->fecha_salida)->format('d-m-Y') . ' --- ' . $grupo->id);
        }

        /**
         * 20/01/2023
         *
         */
        $grupos = DireccionGrupo::query()
            ->activo()
            ->whereIn('condicion_envio_code', [
                Pedido::RECEPCIONADO_OLVA_INT,
                Pedido::EN_CAMINO_OLVA_INT,
            ])
            ->whereDate('fecha_salida', '<=', now()->startOfDay()->subDays(5))
            ->get();

        foreach ($grupos as $grupo) {
            DireccionGrupo::cambiarCondicionEnvio($grupo, Pedido::EN_TIENDA_AGENTE_OLVA_INT);
            $this->info("Grupo (5): " . optional($grupo->fecha_salida)->format('d-m-Y') . ' --- ' . $grupo->id);
        }

        return 0;
    }
}
