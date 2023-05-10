<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDetallepedidosUserregistro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_pedidos', function (Blueprint $table) {
            if(!Schema::hasColumn('detalle_pedidos','user_reg'))
            {
                $table->integer('user_reg')->nullable()->default(0)->after('fecha_recepcion');
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
        Schema::table('detalle_pedidos', function (Blueprint $table) {
            //
        });
    }
}
