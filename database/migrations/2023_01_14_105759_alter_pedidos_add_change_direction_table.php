<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosAddChangeDirectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos',function (Blueprint $table){
            $table->timestamp('cambio_direccion_at')->nullable()->after('returned_at');
        });
        Schema::table('direccion_grupos',function (Blueprint $table){
            $table->timestamp('cambio_direccion_at')->nullable();
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
