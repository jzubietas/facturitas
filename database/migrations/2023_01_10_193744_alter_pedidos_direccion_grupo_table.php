<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosDireccionGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direccion_grupos', function (Blueprint $table) {
            $table->text('cambio_direccion_sustento')->nullable();
        });
        Schema::table('pedidos', function (Blueprint $table) {
            $table->text('cambio_direccion_sustento')->nullable();
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
