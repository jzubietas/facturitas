<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSituacionclientesClavepedido extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('situacion_clientes', function (Blueprint $table) {
            if(!Schema::hasColumn('situacion_clientes','user_clavepedido'))
            {
                $table->string('user_clavepedido')->nullable()->default('')->after('user_identificador');
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
        Schema::table('situacion_clientes', function (Blueprint $table) {
            //
        });
    }
}
