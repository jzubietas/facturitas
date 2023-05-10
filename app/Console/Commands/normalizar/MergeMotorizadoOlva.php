<?php

namespace App\Console\Commands\normalizar;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class MergeMotorizadoOlva extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'direcciongrupo:merge:olva';

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
        $grupos = DireccionGrupo::query()->activo()
            ->where('condicion_envio_code', Pedido::MOTORIZADO_INT)
            ->where('distribucion', 'OLVA')
            ->get();
        if ($grupos->count() > 0) {
            $first = $grupos->pop();

            foreach ($grupos as $p) {
                $p->pedidos()->update([
                    'direccion_grupo' => $first->id
                ]);
                DireccionGrupo::restructurarCodigos($p);
            }
            DireccionGrupo::restructurarCodigos($first);
        }

        return 0;
    }
}
