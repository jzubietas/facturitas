<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMetasTablePedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('metas',function (Blueprint $table){
            if(!Schema::hasColumn('metas','meta_pedido'))
            {
                $table->int('meta_pedido')->nullable()->comment('');//->after('mes');
                $table->int('meta_cobro')->nullable()->comment('');//->after('meta_pedido');
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
