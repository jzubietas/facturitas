<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClientesColsChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if(!Schema::hasColumn('clientes','chateado'))
            {
                $table->integer('chateado')->nullable()->default(0)->coment('chateado');
            }
            if(!Schema::hasColumn('clientes','asesor_chateado'))
            {
                $table->string('asesor_chateado')->nullable()->default('')->after('chateado');
            }
            if(!Schema::hasColumn('clientes','user_chateado'))
            {
                $table->integer('user_chateado')->nullable()->default(0)->coment('chateado');
            }
            if(!Schema::hasColumn('clientes','fecha_chateado'))
            {
                $table->dateTime('fecha_chateado')->nullable()->default('1900-01-01');
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
        Schema::table('clientes', function (Blueprint $table) {
            //
        });
    }
}
