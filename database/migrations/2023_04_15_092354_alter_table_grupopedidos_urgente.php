<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableGrupopedidosUrgente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grupo_pedidos', function (Blueprint $table) {
            if(!Schema::hasColumn('grupo_pedidos','urgente'))
            {
                $table->integer('urgente')->nullable()->default(0)->after('id');
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
        Schema::table('grupo_pedidos', function (Blueprint $table) {
            //
        });
    }
}
