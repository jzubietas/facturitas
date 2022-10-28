<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoBancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_bancarios', function (Blueprint $table) {
            $table->id();
            $table->string('banco')->nullable();
            $table->string('titular')->nullable();
            $table->decimal('importe',10,2)->nullable();
            $table->string('tipo')->nullable();//TIPO DE MOVIMIENTO
            $table->integer('pago')->nullable();//ENLAZADO CON PAGO
            $table->integer('estado')->nullable();

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
        Schema::dropIfExists('movimiento_bancarios');
    }
}
