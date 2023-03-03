<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsMetas2Llamadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('metas', function (Blueprint $table) {
        $table->integer('cliente_nuevo_2')->default(0)->nullable();
        $table->integer('cliente_recurrente_2')->default(0)->nullable();
        $table->integer('cliente_recuperado_abandono_2')->default(0)->nullable();
        $table->integer('cliente_recuperado_reciente_2')->default(0)->nullable();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('metas', function (Blueprint $table) {
          //
        });
    }
}
