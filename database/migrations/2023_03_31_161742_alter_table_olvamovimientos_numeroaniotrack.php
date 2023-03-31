<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableOlvamovimientosNumeroaniotrack extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('olva_movimientos', function (Blueprint $table) {
            //
            $table->string("numerotrack")->nullable()->default('');
            $table->string("aniotrack",2)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('olva_movimientos', function (Blueprint $table) {
            //
        });
    }
}
