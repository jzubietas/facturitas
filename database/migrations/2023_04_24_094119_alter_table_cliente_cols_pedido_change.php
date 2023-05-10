<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClienteColsPedidoChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->decimal('fsb_porcentaje',2,1)->nullable()->default(0)->comment('fisico sin banca')->change();
            $table->decimal('fcb_porcentaje',2,1)->nullable()->default(0)->comment('fisico con banca')->change();
            $table->decimal('esb_porcentaje',2,1)->nullable()->default(0)->comment('electronico sin banca')->change();
            $table->decimal('ecb_porcentaje',2,1)->nullable()->default(0)->comment('electronico con banca')->change();
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
