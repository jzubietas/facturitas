<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class MigrateCondicionEnvioGrupoPedido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:condicion_evio:grupopedido';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected $table = [];
    protected $progress;

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
        $query = DireccionGrupo::query()->activo()
            ->orderBy('created_at');
        $this->progress = $this->output->createProgressBar($query->count());

        $query->chunk(1000, function ($grupos) {
            foreach ($grupos as $grupo) {
                $grupo->pedidos()->activo()
                    ->update([
                        'condicion_envio_code' => $grupo->condicion_envio_code,
                        'condicion_envio' => $grupo->condicion_envio,
                    ]);
                $this->progress->advance();
            }
        });
        $this->progress->finish();
        return 0;
    }
}
