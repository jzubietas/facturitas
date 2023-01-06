<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupo_pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('zona');
            $table->string('provincia');
            $table->string('distrito');
            $table->string('direccion');
            $table->string('referencia')->nullable();
            $table->string('cliente_recibe')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('grupo_pedido_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("pedido_id");
            $table->unsignedBigInteger("grupo_pedido_id");
            $table->string('razon_social');
            $table->string('codigo');

            $table->foreign('pedido_id')->on('pedidos')->references('id')->onUpdate('cascade');
            $table->foreign('grupo_pedido_id')->on('grupo_pedidos')->references('id')->onUpdate('cascade')->onDelete('cascade');
            $table->unique([
                'pedido_id',
                'grupo_pedido_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupo_pedidos');
    }
}
