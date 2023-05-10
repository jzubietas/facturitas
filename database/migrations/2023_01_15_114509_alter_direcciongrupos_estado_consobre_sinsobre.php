<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDirecciongruposEstadoConsobreSinsobre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('direccion_grupos',function (Blueprint $table){
            if(!Schema::hasColumn('direccion_grupos','estado_consinsobre'))
            {
                $table->integer('estado_consinsobre')->default('0')->after('cambio_direccion_at')->comment('es la columna para etiquetar si es con sobre o sin sobre');
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
