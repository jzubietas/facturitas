<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosFecharecepcionOpcourierTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->string('identificador',255)->nullable()->after('user_id');
            $table->string('exidentificador',100)->nullable()->after('identificador');
            $table->string('icelular_asesor',1)->nullable()->after('exidentificador');
            $table->string('icelular_cliente',1)->nullable()->after('icelular_asesor');
            //

            if(!Schema::hasColumn('pedidos','fecha_envio_op_courier'))
            {
            $table->timestamp('fecha_envio_op_courier')->nullable()->after('fecha_recepcion_courier');
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
