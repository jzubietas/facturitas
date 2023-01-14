<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosFecharecepcionOpcourierTablesCelular extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('pedidos', function (Blueprint $table) {
            if(!Schema::hasColumn('pedidos','celular_cliente'))
            {
                $table->string('celular_cliente',255)->nullable()->after('icelular_asesor');
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
