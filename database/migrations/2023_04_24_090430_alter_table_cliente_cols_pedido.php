<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClienteColsPedido extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->timestamp('fecha_ultimopedido')->nullable(false)->default('1900-01-01');
            $table->string('codigo_ultimopedido')->nullable(false);
            $table->integer('pago_ultimopedido')->nullable(0);
            $table->integer('pagado_ultimopedido')->nullable(0);
            $table->decimal('fsb_porcentaje',2,1)->nullable(0)->comment('fisico sin banca');
            $table->decimal('fcb_porcentaje',2,1)->nullable(0)->comment('fisico con banca');
            $table->decimal('esb_porcentaje',2,1)->nullable(0)->comment('electronico sin banca');
            $table->decimal('ecb_porcentaje',2,1)->nullable(0)->comment('electronico con banca');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            //
        });
    }
}
