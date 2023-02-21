<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosCorrecionEstado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
  public function up()
  {

    Schema::table('pedidos', function (Blueprint $table) {
      $table->dropColumn('estado_correccion');
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('pedidos', function (Blueprint $table) {
      $table->integer('estado_correccion',11)->nullable()->comment('');
    });
  }
}
