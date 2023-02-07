<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidossTablesExcondicion extends Migration
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
            if(!Schema::hasColumn('pedidos','condicion_envio_anterior'))
            {
                $table->string('condicion_envio_anterior',255);
            }
            if(!Schema::hasColumn('pedidos','condicion_envio_code_anterior'))
            {
                $table->integer('condicion_envio_code_anterior');
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
