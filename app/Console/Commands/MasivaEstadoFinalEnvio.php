<?php

namespace App\Console\Commands;

use App\Models\DireccionGrupo;
use App\Models\Pedido;
use Illuminate\Console\Command;

class MasivaEstadoFinalEnvio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'masiva:estadofinal:envio';

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
        $pedidos = Pedido::query()->activo()
            //->where('pedidos.condicion_envio_code', '=', Pedido::ENVIO_COURIER_JEFE_OPE_INT)
            ->whereIn('codigo',['B-3012-5',
                'B-3012-4',
                'B-2812-10',
                'B-0201-9',
                'B-0201-11',
                'B-0201-12'])
            ->get();
        $count = $pedidos->count();
        $result = [];
        $progress = $this->output->createProgressBar($count);
        foreach ($pedidos as $pedido)
        {
            $grupo=DireccionGrupo::createByPedido($pedido);
            DireccionGrupo::cambiarCondicionEnvio($grupo,Pedido::ENTREGADO_SIN_SOBRE_CLIENTE_INT);
            $progress->advance();
        }
        $progress->finish();

        return 0;
    }
}
