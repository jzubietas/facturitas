<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use Illuminate\Console\Command;

class NormalizarEstadoPedido extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pedido:estado:normalizar';

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
        Pedido::whereEstado('0')
            ->where('condicion', '<>', Pedido::ANULADO)
            ->update([
                "condicion" => Pedido::ANULADO,
                "condicion_code" => Pedido::ANULADO_INT,
                "condicion_int" => Pedido::ANULADO_INT,
            ]);
        return 0;
    }
}
