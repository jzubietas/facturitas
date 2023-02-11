<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablaDireccionJefeOP extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla_direccion_jefeop', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->string('rol')->nullable();
            $table->string('distrito')->nullable();
            $table->string('direccion_recojo')->nullable();
            $table->string('numero_recojo',9)->nullable();
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
        //*Schema::dropIfExists('tabla_direccion_jefe_o_p');
    }
}
