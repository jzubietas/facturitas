<?php

namespace App\Console\Commands\automatic;

use App\Jobs\SyncOlvaJob;
use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class ResetSendScreenEncargadoOlvaSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'olva:encargado:tienda_reset';

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
            ->where('condicion_envio_code', Pedido::EN_TIENDA_AGENTE_OLVA_INT)
            ->whereNull('add_screenshot_at')
            ->get();
        foreach ($grupos as $grupo) {
            $g = $grupo->getMedia('tienda_olva_notificado')
                ->sortByDesc(fn($media) => $media->created_at->format('d-m-Y'))
                ->first();
            if ($g != null) {
                $this->info("M ".$g->file_name);
                $grupo->update([
                    'add_screenshot_at' => $g->created_at
                ]);
            }
        }

        return 0;
    }
}
