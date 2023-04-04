<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableHistorialvidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('historial_vidas', function (Blueprint $table) {
            //
            $table->string('accion')->after('user_id')->nullable(true);
            $table->integer('responsable')->after('accion')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historial_vidas', function (Blueprint $table) {
            //
        });
    }
}
