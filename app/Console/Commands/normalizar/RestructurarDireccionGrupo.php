<?php

namespace App\Console\Commands\normalizar;

use App\Models\Cliente;
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
        $clientes=Cliente::query()->activo()
            ->orderBy('id');

        
        $this->progress = $this->output->createProgressBar($clientes->count());
        $clientes->chunk(1000, function ($_clientes) {
            foreach ($_clientes as $cliente) {
                DireccionGrupo::restructurarCodigos('2023','01',$cliente);
                if ($cliente->estado = 1) {
                    $cliente->pedidos()->activo()->update([
                        'condicion_envio' => $cliente->condicion_envio,
                        'condicion_envio_code' => $cliente->condicion_envio_code,
                    ]);
                }
                $this->progress->advance();
            }
        });
        $this->progress->finish();
        return 0;
    }
}
