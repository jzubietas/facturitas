<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StatusPedidoModelChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:statuspedido:model';

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
        /*$MigracionVariablesPedidos = [
            0 => 'ANULADO',
            1 => 'POR ATENDER',
            2 => 'EN PROCESO ATENCIÓN',
            3 => 'ATENDIDO'
        ];*/

        $MigracionVariablesPedidosEnvio = Pedido::$estadosCondicionEnvioCode;

        /*foreach($MigracionVariablesPedidos  as $status => $value){
            Pedido::where('condicion','=', $value)->update([
                'condicion' => $status
            ]);
        }*/

        foreach($MigracionVariablesPedidosEnvio  as $status => $value){
            Pedido::where('condicion_envio_code','=', $status)->update([
                'condicion_envio' => $value
            ]);
        }

        /*Schema::table('pedidos', function (Blueprint $table) {
            $table->integer('condicion')->nullable()->change();
            $table->integer('condicion_envio')->nullable()->change();
        });*/

        return 0;
    }
}
