<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePorcentajesColsCodporcentaje extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('porcentajes', function (Blueprint $table) {
            if(!Schema::hasColumn('porcentajes','cod_porcentaje'))
            {
                $table->string('cod_porcentaje',3)->nullable()->default('')->coment('codigo del tipo porcentaje')->after('cliente_id');
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
        Schema::table('porcentajes', function (Blueprint $table) {
            //
        });
    }
}
