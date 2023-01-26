<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class FinaliceOlvaNoTracking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finalize:olva:no-tracking';

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
        $grupos = DireccionGrupo::query()->inOlvaAll()->activo()
            ->where('direccion', 'NOT REGEXP', '^[0-9]+$')
            ->get();
        foreach ($grupos as $grupo) {
            DireccionGrupo::cambiarCondicionEnvio($grupo, Pedido::ENTREGADO_PROVINCIA_INT,[
                'courier_failed_sync_at'=>null
            ]);
        }

       DireccionGrupo::query()->inOlvaAll()->activo()
            ->inOlvaFinalizado()
            ->update([
                'courier_failed_sync_at'=>null
            ]);

        return 0;
    }
}
