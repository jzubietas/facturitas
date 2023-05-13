<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClientesColsLlamado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if(!Schema::hasColumn('clientes','llamado'))
            {
                $table->integer('llamado')->nullable()->default(0)->coment('llamado');
            }
            if(!Schema::hasColumn('clientes','asesor_llamado'))
            {
                $table->string('asesor_llamado')->nullable()->default('')->after('llamado');
            }
            if(!Schema::hasColumn('clientes','user_llamado'))
            {
                $table->integer('user_llamado')->nullable()->default(0)->coment('llamado');
            }
            if(!Schema::hasColumn('clientes','fecha_llamado'))
            {
                $table->dateTime('fecha_llamado')->nullable()->default('1900-01-01');
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
