<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleContactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_contactos', function (Blueprint $table) {
            $table->id();
          $table->integer('codigo_asesor');
          $table->string('nombre_asesor');
          $table->string('celular',9);
          $table->integer('codigo_cliente');
          $table->string('nombres_cliente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_contactos');
    }
}
