<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class RestructurarDireccionGrupo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'direcciongrupo:restructurar:codigos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var ProgressBar
     */
    protected $progress = null;

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
            ->where(function ($query) {
                $query->where('estado', '=', '1');
                $query->orWhere(function ($query) {
                    $query->where('estado', '=', '0');
                    $query->where('motorizado_status', '<>', '0');
                });
            })
            ->orderBy('created_at');

        $this->progress = $this->output->createProgressBar($query->count());
        $query->chunk(1000, function ($direcciongrupos) {
            foreach ($direcciongrupos as $grupo) {
                DireccionGrupo::restructurarCodigos($grupo);
                if ($grupo->estado = 1) {
                    $grupo->pedidos()->activo()->update([
                        'condicion_envio' => $grupo->condicion_envio,
                        'condicion_envio_code' => $grupo->condicion_envio_code,
                    ]);
                }
                $this->progress->advance();
            }
        });
        $this->progress->finish();
        return 0;
    }
}
