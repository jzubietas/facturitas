<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableSituacionClientesUservidaIdentificador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('situacion_clientes',function (Blueprint $table){
            if(!Schema::hasColumn('situacion_clientes','user_id'))
            {
                $table->integer('user_id')->nullable()->after('cliente_id');
            }
            if(!Schema::hasColumn('situacion_clientes','user_identificador'))
            {
                $table->integer('user_identificador')->nullable()->after('user_id');
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
