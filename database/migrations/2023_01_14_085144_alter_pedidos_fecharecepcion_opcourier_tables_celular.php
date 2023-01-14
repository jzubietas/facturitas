<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosFecharecepcionOpcourierTablesCelular extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pedidos', function (Blueprint $table) {
            if(!Schema::hasColumn('pedidos','fecha_envio_op_courier'))
            {
                $table->timestamp('celular_cliente')->nullable()->after('icelular_asesor');
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
