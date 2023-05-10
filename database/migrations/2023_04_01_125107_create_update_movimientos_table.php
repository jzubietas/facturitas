<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('update_movimientos', function (Blueprint $table) {
            $table->id();
            $table->text('obs')->nullable(true);
            $table->text('valores_ant');
            $table->text('valores_act');
            $table->timestamp('fecha_creacion')->nullable(false);
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
        Schema::dropIfExists('update_movimientos');
    }
}
