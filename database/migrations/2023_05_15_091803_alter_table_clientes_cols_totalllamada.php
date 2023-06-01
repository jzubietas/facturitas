<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClientesColsTotalllamada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if(!Schema::hasColumn('clientes','total_llamada'))
            {
                $table->integer('total_llamadas')->nullable()->default(0)->coment('llamadas');
            }
            if(!Schema::hasColumn('clientes','total_chat'))
            {
                $table->integer('total_chats')->nullable()->default(0)->coment('chats');
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
