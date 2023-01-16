<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidoDireccionGrupoFechaCondicionEnvioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->timestamp('condicion_envio_at')->nullable()->after('condicion_envio_code');
        });
        Schema::table('direccion_grupos', function (Blueprint $table) {
            $table->timestamp('condicion_envio_at')->nullable()->after('condicion_envio_code');
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
