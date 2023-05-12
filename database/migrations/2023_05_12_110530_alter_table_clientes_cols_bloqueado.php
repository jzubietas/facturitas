<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClientesColsBloqueado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if(!Schema::hasColumn('clientes','bloqueado'))
            {
                $table->integer('bloqueado')->nullable()->default(0)->coment('bloqueado')->after('congelado');
            }
            if(!Schema::hasColumn('clientes','sust_bloqueado'))
            {
                $table->string('sust_bloqueado')->nullable()->default('')->coment('sustento bloqueado')->after('bloqueado');
                $table->string('sust_otro_bloqueado')->nullable()->default('')->coment('sustento otro bloqueado')->after('sust_bloqueado');
            }
            if(!Schema::hasColumn('clientes','user_bloqueado_id'))
            {
                $table->integer('user_bloqueado_id')->default(0);
            }
            if(!Schema::hasColumn('clientes','responsable_bloqueo'))
            {
                $table->string('responsable_bloqueo')->nullable()->default('')->coment('responsable_bloqueo')->after('user_bloqueado_id');
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
