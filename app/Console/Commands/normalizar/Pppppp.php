<?php

namespace App\Console\Commands\normalizar;

use App\Models\DireccionGrupo;
use Illuminate\Console\Command;

class Pppppp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:direcciongrupo:pedidos';

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
        $direcciongrupo = DireccionGrupo::query()->activo()->get();
        $progress=$this->output->createProgressBar($direcciongrupo->count());
        foreach ($direcciongrupo as $grupo) {
            DireccionGrupo::restructurarCodigos($grupo);
            $data = [
                'condicion_envio' => $grupo->condicion_envio,
                'condicion_envio_code' => $grupo->condicion_envio_code,
                'condicion_envio_at' =>  $grupo->condicion_envio_at,
            ];
            //$grupo->update($data);
            $grupo->pedidos()->update($data);
            $progress->advance();
        }
        $progress->finish();
        return 0;
    }
}
