<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');//asignar a cada asesor
            $table->string('nombre')->nullable();
            $table->integer('celular')->unique();
            $table->integer('tipo')->nullable();//base fria o cliente         
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->string('direccion')->nullable();
            $table->string('referencia')->nullable();             
            $table->string('dni')->nullable();
            $table->decimal('saldo',10,2)->nullable();
            $table->integer('deuda')->nullable();
            $table->integer('pidio')->nullable();
            $table->integer('estado');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
