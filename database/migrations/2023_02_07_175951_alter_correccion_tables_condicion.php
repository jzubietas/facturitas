<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCorreccionTablesCondicion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('corrections',function (Blueprint $table){
            if(!Schema::hasColumn('corrections','condicion_envio'))
            {
                $table->string('condicion_envio',255)->nullable()->after('asesor_identify');
            }
            if(!Schema::hasColumn('corrections','condicion_envio_code'))
            {
                $table->integer('condicion_envio_code')->nullable()->after('condicion_envio');
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
