<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class DireccionGrupoZona extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'direcciongrupo:zona';

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
        $dir = DireccionGrupo::query()->with('motorizado')
            ->activo()
            ->where('condicion_envio_code', Pedido::REPARTO_COURIER_INT)
            ->whereNotNull('motorizado_id')
            ->get();

        foreach ($dir as $item) {
            $item->update([
                'distribucion' => $item->motorizado->zona
            ]);
        }
        return 0;
    }
}
