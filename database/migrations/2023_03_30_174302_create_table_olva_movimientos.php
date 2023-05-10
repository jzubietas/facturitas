<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOlvaMovimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('olva_movimientos');
        Schema::create('olva_movimientos', function (Blueprint $table) {
            $table->id();
            $table->string('obs')->default('')->nullable();
            $table->string('nombre_sede')->default('')->nullable();
            $table->timestamp("fecha_creacion");
            $table->string('estado_tracking')->default('')->nullable();
            $table->integer('event_id')->default(0)->nullable();
            $table->integer('status')->default(0)->nullable();
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
        Schema::table('olva_movimientos', function (Blueprint $table) {
            //
        });
    }
}
