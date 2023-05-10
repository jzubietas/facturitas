<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadosDetallecontactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_contactos', function (Blueprint $table) {
          $table->boolean('guardado')->default(false);
          $table->boolean('confirmado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_contactos', function (Blueprint $table) {
          $table->boolean('guardado')->default(false);
          $table->boolean('confirmado')->default(false);
        });
    }
}
