<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('pagopedido_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cliente_id');
            $table->decimal('total_cobro',10,2)->nullable();
            $table->decimal('total_pagado',10,2)->nullable();
            $table->string('observacion')->nullable();
            $table->string('condicion')->nullable();//DEBE, CANCELADO, PENDIENTE DE REVISION
            $table->string('notificacion')->nullable();
            /* $table->decimal('saldo',10,2)->nullable(); */
            $table->decimal('diferencia',10,2)->nullable();
            $table->date('fecha_aprobacion')->nullable();//CUANDO APRUEBAN EL PAGO
            $table->integer('estado');

            $table->timestamps();

            //$table->foreign('pagopedido_id')->references('id')->on('pago_pedidos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}
