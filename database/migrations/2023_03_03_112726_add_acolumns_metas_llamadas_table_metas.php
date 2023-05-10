<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcolumnsMetasLlamadasTableMetas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('metas', function (Blueprint $table) {
          $table->integer('cliente_nuevo')->default(0)->nullable();
          $table->integer('cliente_recurrente')->default(0)->nullable();
          $table->integer('cliente_recuperado_abandono')->default(0)->nullable();
          $table->integer('cliente_recuperado_reciente')->default(0)->nullable();
          $table->integer('meta_pedido')->default(0)->nullable()->change();
          $table->integer('meta_pedido_2')->default(0)->nullable()->change();
          $table->integer('meta_cobro')->default(0)->nullable()->change();
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
