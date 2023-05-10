<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDetallepagosUserregistro2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_pagos', function (Blueprint $table) {
            if(!Schema::hasColumn('detalle_pagos','user_reg'))
            {
                $table->integer('user_reg')->nullable()->default(0)->after('observacion');
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
        Schema::table('detalle_pagos', function (Blueprint $table) {
            //
        });
    }
}
