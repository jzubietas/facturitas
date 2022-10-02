<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRucsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rucs', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('user_id');//ASIGNAR A ASESOR
            $table->unsignedBigInteger('cliente_id');//ASIGNAR A CLIENTE
            $table->string('num_ruc')->unique();//NUMERO DE RUC
            $table->string('empresa')->nullable();
            $table->integer('estado')->nullable();
            
            $table->timestamps();

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
        Schema::dropIfExists('rucs');
    }
}
