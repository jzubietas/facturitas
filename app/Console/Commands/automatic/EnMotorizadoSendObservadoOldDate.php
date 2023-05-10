<?php

namespace App\Console\Commands\automatic;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class EnMotorizadoSendObservadoOldDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motorizado:send-observado';

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
        if (now() < now()->endOfDay()->startOfMinute()) {
            $ask = $this->confirm("Se esta ejecutando antes de las 11:59 M,¿Continuar?");
            if (!$ask) {
                return 0;
            }
        }

        DireccionGrupo::query()
            ->where('condicion_envio_code', Pedido::MOTORIZADO_INT)
            ->activo()
            ->whereDate('fecha_salida', '<', now())
            ->update([
                'motorizado_status' => Pedido::ESTADO_MOTORIZADO_OBSERVADO,
                'motorizado_sustento_text' => 'No entregado por el motorizado (hecho por el sistema)'
            ]);

        return 0;
    }
}
