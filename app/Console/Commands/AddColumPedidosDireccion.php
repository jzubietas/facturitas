<?php

namespace App\Console\Commands;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;

class AddColumPedidosDireccion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:add-pedidos-direccion-dg';

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
        /*\Schema::table('pedidos', function (Blueprint $table) {
            $table->string("env_destino",250)->nullable()->after('estado_sobre')->comment('LIMA PROVINCIA');//LIMA PROVINCIA
            $table->string("env_distrito",250)->nullable()->after('env_destino')->comment('LIMA PROVINCIA');//LIMA - provincia
            $table->string("env_zona",250)->nullable()->after('env_distrito')->comment('NORTE SUR ESTE OESTE para LIMA PROVINCIA');//NORTE SUR ESTE OESTE para LIMA PROVINCIA
            $table->string("env_zona_asignada",250)->nullable()->after('env_zona')->comment('ZONA CONFIRMADA PARA ENVIAR A MOTORIZADO');//NORTE SUR ESTE OESTE para LIMA PROVINCIA
            $table->string("env_nombre_cliente_recibe",250)->nullable()->after('env_zona_asignada')->comment('LIMA o PROVINCIA');//LIMA o PROVINCIA
            $table->string("env_celular_cliente_recibe",250)->nullable()->after('env_nombre_cliente_recibe')->comment('LIMA');//olva
            $table->string("env_cantidad",250)->nullable()->after('env_celular_cliente_recibe');
            $table->string("env_direccion",250)->nullable()->after('env_cantidad')->comment('direccion lima provincia');//tracking
            $table->string("env_tracking",250)->nullable()->after('env_direccion')->comment('tracking provincia');//tracking
            $table->string("env_referencia",250)->nullable()->after('env_tracking')->comment('');//registro
            $table->string("env_numregistro",250)->nullable()->after('env_referencia')->comment('');
            $table->string("env_rotulo",250)->nullable()->after('env_numregistro');//rotulo
            $table->string("env_observacion",250)->nullable()->after('env_rotulo');//
            $table->string("env_importe",250)->nullable()->after('env_observacion');//importe
            //$table->integer("direccion_grupo")->nullable()->after('env_importe');
            //$table->integer("celular_cliente_recibe",250)->nullable()->after('nombre_cliente');
        });*/

        \Schema::table('pedidos', function (Blueprint $table) {
            $table->integer("direccion_grupo")->nullable()->after('estado_ruta');
        });





        return 0;
    }
}
