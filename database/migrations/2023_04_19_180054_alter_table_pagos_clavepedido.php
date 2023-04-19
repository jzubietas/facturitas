<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePagosClavepedido extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            if(!Schema::hasColumn('clientes','user_identificador'))
            {
                $table->string('user_identificador')->nullable()->default('')->after('user_id');
            }
            if(!Schema::hasColumn('pagos','user_clavepedido'))
            {
                $table->string('user_clavepedido')->nullable()->default('')->after('user_identificador');
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
        Schema::table('pagos', function (Blueprint $table) {
            //
        });
    }
}
