<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pago_id');
            $table->unsignedBigInteger('pedido_id');
            $table->integer('pagado')->nullable();//ESTADO DEL PAGO DEL PEDIDO
            $table->decimal('abono',2,1)->nullable();//CANTIDAD ABONADA EN ESTE PAGO
            $table->integer('estado');

            $table->timestamps();

            $table->foreign('pago_id')->references('id')->on('pagos');
            $table->foreign('pedido_id')->references('id')->on('pedidos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pago_pedidos');
    }
}
