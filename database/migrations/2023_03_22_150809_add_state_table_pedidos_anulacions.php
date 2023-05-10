<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateTablePedidosAnulacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_anulacions', function (Blueprint $table) {
            $table->integer('state_solicitud')->nullable(true)->default(1)->comment('1: Activo, 0: Inactivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos_anulacions', function (Blueprint $table) {
            //
        });
    }
}
