<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsMetas3Llamadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('metas', function (Blueprint $table) {
        $table->integer('meta_quincena_nuevo')->default(0)->nullable();
        $table->integer('meta_quincena_recuperado_abandono')->default(0)->nullable();
        $table->integer('meta_quincena_recuperado_reciente')->default(0)->nullable();
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
