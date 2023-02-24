<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditColumnIdentificadorDetecontactosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detalle_contactos', function (Blueprint $table) {
          $table->string('codigo_asesor')->change();
          $table->integer('codigo_registra');
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
          $table->string('codigo_asesor')->change();
          $table->integer('codigo_registra');
        });
    }
}
