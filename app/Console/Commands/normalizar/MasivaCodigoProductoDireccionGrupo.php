<?php

namespace App\Console\Commands\normalizar;

use App\Models\DireccionGrupo;
use Illuminate\Console\Command;

class MasivaCodigoProductoDireccionGrupo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'masiva:codigoproducto:direcciongrupo';

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
        $grupos = DireccionGrupo::query()->activo()
            //->where('pedidos.condicion_envio_code', '=', Pedido::ENVIO_COURIER_JEFE_OPE_INT)
            ->get();
        $count = $grupos->count();
        $result = [];
        $progress = $this->output->createProgressBar($count);
        foreach ($grupos as $grupo)
        {
            DireccionGrupo::restructurarCodigos($grupo);
            $progress->advance();
        }
        $progress->finish();

        return 0;
    }
}
