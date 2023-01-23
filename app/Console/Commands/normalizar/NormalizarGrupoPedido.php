<?php

namespace App\Console\Commands\normalizar;

use App\Models\GrupoPedido;
use App\Models\Pedido;
use Illuminate\Console\Command;

class NormalizarGrupoPedido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalizar:grupo-pedido';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        \Schema::disableForeignKeyConstraints();
        \DB::table('grupo_pedido_items')->truncate();
        GrupoPedido::truncate();
        \Schema::enableForeignKeyConstraints();

        $query = Pedido::query()->activo()
            ->where('condicion_envio_code', Pedido::RECEPCION_COURIER_INT)
            ->where('estado_sobre', '1')
            ->orderBy('pedidos.id');
        $this->progress = $this->output->createProgressBar($query->count());
        $query->chunk(1000, function ($pedidos) {
            foreach ($pedidos as $pedido) {
                GrupoPedido::createGroupByPedido($pedido, false, true);
                $this->progress->advance();
            }
        });
        $this->progress->finish();
        return 0;
    }
}
