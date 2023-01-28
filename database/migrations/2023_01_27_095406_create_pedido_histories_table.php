<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('pedido_histories');
        Schema::create('pedido_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('identificador',4);
            $table->string('cliente_id',50);
            $table->string('ruc',12);
            $table->string('empresa');
            $table->string('mes',4);
            $table->string('year',4);
            $table->string('cantidad');
            $table->string('tipo_banca');
            $table->text('descripcion');
            $table->text('nota');
            $table->string('courier_price');
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
        Schema::dropIfExists('pedido_histories');
    }
}
