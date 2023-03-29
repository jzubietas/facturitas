<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsResponsablesTablePedidosAnulacions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos_anulacions', function (Blueprint $table) {
            $table->string('resposable_create_asesor')->nullable(true)->comment("Responsable al solicitar la anulacion por el asesor");
            $table->string('resposable_aprob_encargado')->nullable(true)->comment("Responsable al aprobar la anulacion por el encargado");
            $table->string('files_responsable_asesor')->nullable(true)->comment("Ids de los archivos subidos a la tabla file_upload_anulacions");
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
