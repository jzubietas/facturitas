<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAcolumnTipoTablePedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_contactos', function (Blueprint $table) {
            $table->integer('tipo_insert')->default(1)->nullable()->comment('1:Nuevo, 2:Cambio nombre, 3:Bloqueo ,4:Cambio Numero');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_contactos', function (Blueprint $table) {
          $table->dropColumn('tipo_insert');
        });
    }
}
