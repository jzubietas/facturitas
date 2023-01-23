<?php

namespace App\Console\Commands\normalizar;

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
        $this->info("cantidad: " . $grupos->count());
        foreach ($grupos as $grupo) {
            $this->warn("Dividiendo ...  ENV-" . $grupo->id);
            DireccionGrupo::dividirCondicionEnvioOlva($grupo);
            $this->info("Dividiendo Success ENV-" . $grupo->id);
        }
        return 0;
    }
}
