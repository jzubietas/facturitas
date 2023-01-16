<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class DestruirGrupos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grupos:restructurar:noterminado';

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
        $query = DireccionGrupo::query()
            ->whereNotIn('condicion_envio_code', [
                Pedido::ENTREGADO_CLIENTE_INT,
                Pedido::ENTREGADO_SIN_SOBRE_CLIENTE_INT,
            ])
            ->where(function ($query){
                $query->where('estado', '=', '1');
                $query->orWhere(function ($query){
                    $query->where('estado', '=', '0');
                    $query->where('motorizado_status', '<>', '0');
                });
            })
            ->orderBy('created_at');

        $this->progress = $this->output->createProgressBar($query->count());
        $query->chunk(1000, function ($direcciongrupos) {
            foreach ($direcciongrupos as $grupo) {
                DireccionGrupo::restructurarCodigos($grupo);
                $this->progress->advance();
            }
        });
        $this->progress->finish();
        return 0;
    }
}
