<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosAnulacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_anulacions', function (Blueprint $table) {
            $table->id();
            $table->integer('pedido_id');

            $table->integer('user_id_asesor')->nullable(true);
            $table->string('motivo_solicitud')->nullable(true);
            $table->string('foto_solicitud')->nullable(true);
            $table->integer('estado_aprueba_asesor')->nullable(true);

            $table->integer('user_id_encargado')->nullable(true);
            $table->string('motivo_sol_encargado')->nullable(true);
            $table->string('foto_sol_encargado')->nullable(true);
            $table->integer('estado_aprueba_encargado')->nullable(true);

            $table->integer('user_id_administrador')->nullable(true);
            $table->string('motivo_sol_admin')->nullable(true);
            $table->string('foto_sol_admin')->nullable(true);
            $table->integer('estado_aprueba_administrador')->nullable(true);

            $table->integer('user_id_jefeop')->nullable(true);
            $table->string('motivo_jefeop_admin')->nullable(true);
            $table->string('foto_jefeop_admin')->nullable(true);
            $table->integer('estado_aprueba_jefeop')->nullable(true);

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
        Schema::dropIfExists('pedidos_anulacions');
    }
}
