<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidoAndGrupoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->text('env_gmlink')->nullable()
                ->after('env_observacion')
                ->comment("Link de google maps");
        });
        Schema::table('direccion_grupos', function (Blueprint $table) {
            $table->text('gmlink')->nullable()
                ->after('observacion')
                ->comment("Link de google maps");
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
