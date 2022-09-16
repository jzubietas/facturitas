<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePorcentajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('porcentajes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');//asignar a cada cliente
            $table->string('nombre')->nullable();
            $table->decimal('porcentaje',2,1)->nullable();

            $table->timestamps();

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
        Schema::dropIfExists('porcentajes');
    }
}
