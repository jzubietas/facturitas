<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsResponsables2TablePedidosAnulacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_anulacions', function (Blueprint $table) {
            $table->string('resposable_aprob_admin')->nullable(true)->comment("Responsable al aprobar la anulacion por el Administrador");
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
