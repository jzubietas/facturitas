<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('pago')->nullable();// 0 - 1
            $table->integer('pagado')->nullable();// 0 - 1
            $table->integer('envio')->nullable();// 0 - 1
            $table->string('destino')->nullable();//LIMA - PROVINCIA
            $table->string('trecking')->nullable();//NUMERO DE TRECKING Y/O GUIA
            $table->string('direccion')->nullable();//DIRECCION DE ENVIO
            $table->string('condicion_envio')->nullable();//POR ENTREGAR - EN REPARTO - ENTREGADO
            $table->string('condicion')->nullable();//REGISTRADO-EN PROCESO-ENTREGADO-PAGADO-ANULADO
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo')->nullable();//MOTIVO DE ANULACION
            $table->string('responsable')->nullable();//RESPONSABLE DE ANULACION
            $table->string('modificador')->nullable();//ULTIMO USUARIO QUE MODIFICA EL PEDIDO
            $table->integer('estado');

            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
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
        Schema::dropIfExists('pedidos');
    }
}
