<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pago_id');
            $table->decimal('monto',10,2);
            $table->string('banco')->nullable();
            $table->string('imagen')->nullable();//captura del pago
            $table->date('fecha')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('titular')->nullable();
            $table->date('fecha_deposito')->nullable();
            $table->string('observacion')->nullable();//si aprueban o ingresar observacion
            $table->integer('estado');

            $table->timestamps();

            $table->foreign('pago_id')->references('id')->on('pagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pagos');
    }
}
