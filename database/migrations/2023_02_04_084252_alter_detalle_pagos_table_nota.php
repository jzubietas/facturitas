<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDetallePagosTableNota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('detalle_pagos',function (Blueprint $table){
            if(!Schema::hasColumn('detalle_pagos','nota'))
            {
                $table->string('nota',250)->nullable()->comment('')->after('fecha_deposito');
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
