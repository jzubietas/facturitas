<?php

namespace App\Console\Commands\normalizar;

use App\Models\DireccionGrupo;
use Illuminate\Console\Command;

class SeguimientoOlva extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:seguimiento-olva';

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
        $grupos = DireccionGrupo::activo()->inOlva()->whereNotNull('courier_failed_sync_at')->get();
        $unique = [];
        foreach ($grupos as $grupo) {
            $unique[$grupo->distribucion . '_' . $grupo->direccion][] = $grupo;
        }
        foreach ($unique as $grupos) {
            if (count($grupos) > 1) {
                $first = array_pop($grupos);
                foreach ($grupos as $g) {
                    $g->pedidos()->update([
                        'direccion_grupo' => $first->id
                    ]);
                    DireccionGrupo::restructurarCodigos($g);
                }
                DireccionGrupo::restructurarCodigos($first);
            }
        }
        return 0;
    }
}
