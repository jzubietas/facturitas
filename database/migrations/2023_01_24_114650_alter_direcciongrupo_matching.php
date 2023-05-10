<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDirecciongrupoMatching extends Migration
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
            if(!Schema::hasColumn('direccion_grupos','relacionado'))
            {
                $table->integer('relacionado')->default('0')->comment('es la columna para etiquetar relacionado con courier');
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
