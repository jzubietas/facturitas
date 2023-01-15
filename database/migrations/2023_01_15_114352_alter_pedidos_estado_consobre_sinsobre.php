<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosEstadoConsobreSinsobre extends Migration
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
            if(!Schema::hasColumn('pedidos','estado_consinsobre'))
            {
                $table->integer('estado_consinsobre')->default('0')->after('estado_sobre')->comment('es la columna para etiquetar si es con sobre o sin sobre');
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
