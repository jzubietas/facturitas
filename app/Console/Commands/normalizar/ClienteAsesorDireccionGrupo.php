<?php

namespace App\Console\Commands\normalizar;

use App\Models\DireccionGrupo;
use Illuminate\Console\Command;

class ClienteAsesorDireccionGrupo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:grupo:cliente_asesor';

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
        $grupos = DireccionGrupo::with('pedidos')->activo()->get();
        foreach ($grupos as $grupo) {
            DireccionGrupo::restructurarCodigos($grupo);
            if ($grupo->estado == 0) {
                $this->error('ENV' . $grupo->id);
            } else {
                $pedido = $grupo->pedidos()->first();
                if ($pedido->user_id != $grupo->user_id || $pedido->cliente_id != $grupo->cliente_id||$grupo->identificador!=$pedido->identificador) {
                    $this->warn('DIR ENV' . $grupo->id);
                    $grupo->update([
                        'cliente_id' => $pedido->cliente_id,
                        'user_id' => $pedido->user_id,
                        'identificador' => $pedido->user->identificador,
                    ]);
                }
            }
        }
        return 0;
    }
}
