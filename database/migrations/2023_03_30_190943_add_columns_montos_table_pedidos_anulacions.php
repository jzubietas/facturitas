<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsMontosTablePedidosAnulacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_anulacions', function (Blueprint $table) {
            $table->decimal('cantidad',10,2)->default(0)->nullable(true)->comment("Se copia el valor de la cantidad del pedido");
            $table->decimal('cantidad_resta',10,2)->default(0)->nullable(true)->comment("Resta de cantidad del pedido menos total anular");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos_anulacions', function (Blueprint $table) {
            //
        });
    }
}
