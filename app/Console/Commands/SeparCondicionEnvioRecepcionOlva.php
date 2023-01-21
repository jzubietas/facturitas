<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class SeparCondicionEnvioRecepcionOlva extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'olva:separar';

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
        $grupos = DireccionGrupo::query()
            ->activo()
            ->whereCondicionEnvioCode(Pedido::RECEPCIONADO_OLVA_INT)
            ->get();
        $this->info("cantidad: ".$grupos->count());
        foreach ($grupos as $grupo) {
            $pgrupos = $grupo->pedidos->groupBy(fn(Pedido $pedido) => $pedido->env_zona . '_' . $pedido->env_tracking)->values();
            foreach ($pgrupos as $index => $pgrupo) {
                if ($index > 0) {
                    $model = $grupo->replicate();
                    $model->save();
                    $this->info("gid: ".$model->id);
                    foreach ($pgrupo as $pedido) {
                        $pedido->update([
                            'direccion_grupo' => $model->id
                        ]);
                    }
                    DireccionGrupo::restructurarCodigos($model);
                }
            }
            DireccionGrupo::restructurarCodigos($grupo);
        }
        return 0;
    }
}
