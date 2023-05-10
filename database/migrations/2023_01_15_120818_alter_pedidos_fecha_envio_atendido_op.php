<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosFechaEnvioAtendidoOp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pedidos',function (Blueprint $table){
            if(!Schema::hasColumn('pedidos','fecha_envio_atendido_op'))
            {
                $table->timestamp('fecha_envio_atendido_op')->nullable()->after('fecha_envio_op_courier');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
