<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetallePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->string('codigo');//fecha-correlativoxdia
            $table->string('nombre_empresa');
            $table->string('mes');
            $table->string('anio')->nullable();;
            $table->string('ruc');
            $table->decimal('cantidad',10,2);
            $table->string('adjunto')->nullable();//comprimido del pago
            $table->string('tipo_banca');//fisica o electronica//si o no
            $table->decimal('porcentaje',2,1);
            $table->decimal('ft',10,2);//cantidad*porcentaje//subtotal
            $table->decimal('courier',10,2);
            $table->decimal('total',10,2);
            $table->string('descripcion')->nullable();//foto o PDF(select)
            $table->string('nota')->nullable();//foto o PDF(select)
            $table->string('envio_doc')->nullable();//foto o PDF(select)
            $table->dateTime('fecha_envio_doc')->nullable();//fecha que dio foto o PDF
            $table->integer('cant_compro')->nullable();//cuantas fotos
            $table->date('fecha_envio_doc_fis')->nullable();//cuando sale de la oficina
            $table->string('foto1')->nullable();
            $table->string('foto2')->nullable();
            $table->string('atendido_por')->nullable();
            $table->date('fecha_recepcion')->nullable();//cuando el cliente recibe            
            $table->integer('estado');

            $table->timestamps();

            $table->foreign('pedido_id')->references('id')->on('pedidos');
            //$table->foreign('articulo_id')->references('id')->on('articulos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pedidos');
    }
}
