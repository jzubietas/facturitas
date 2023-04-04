<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsermovimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_movimientos', function (Blueprint $table) {
            //
            $table->id();
            $table->string('accion')->default('')->nullable();
            $table->string('detalle')->default('')->nullable();
            $table->integer('user_id')->default(0)->nullable();
            $table->string('rol')->default('')->nullable();
            $table->timestamp("created_at")->nullable()->default(now());
            $table->timestamp("updated_at")->nullable()->default(now());

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_movimientos', function (Blueprint $table) {
            //
        });
    }
}
