<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidossTablesExpedidooriginal extends Migration
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
            if(!Schema::hasColumn('pedidos','codigo_anterior'))
            {
                $table->string('codigo_anterior',255);
            }
            if(!Schema::hasColumn('pedidos','pedidoid_anterior'))
            {
                $table->bigInteger('pedidoid_anterior');
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
