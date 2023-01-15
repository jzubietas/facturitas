<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MotorizadoAutoNoRecibido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motorizado:auto-no-recibido';

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
        if (now() < now()->startOfDay()->addHours(19)) {
            $ask = $this->confirm("Se esta ejecutando antes de las 07:00 PM,¿Continuar?");
            if (!$ask) {
                return 0;
            }
        }

        $grupos = DireccionGrupo::where('direccion_grupos.condicion_envio_code', Pedido::ENVIO_MOTORIZADO_COURIER_INT)
            ->whereDate('direccion_grupos.fecha_salida', now())
            ->where('direccion_grupos.motorizado_status', '=', 0)
            ->activo()
            ->get();

        foreach ($grupos as $grupo) {
            $motorizado=$grupo->motorizado;
            $grupo->update([
                'motorizado_sustento_text' => 'Este pedido fue Devuelto automáticamente por el sistema ya que no fue Recibido por el motorizado '.optional($motorizado)->zona,
                'motorizado_status' => Pedido::ESTADO_MOTORIZADO_NO_RECIBIDO
            ]);
        }
        return 0;
    }
}
