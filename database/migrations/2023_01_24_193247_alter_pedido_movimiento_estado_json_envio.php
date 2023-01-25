<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidoMovimientoEstadoJsonEnvio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pedido_movimiento_estados',function (Blueprint $table){
            if(!Schema::hasColumn('pedido_movimiento_estados','json_envio'))
            {
                $table->json('json_envio')->nullable()->comment('es la columna para etiquetar info del envio solo del pedido');
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
