<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHistorialChats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_chats', function (Blueprint $table) {
            $table->id();
            $table->string('celular')->nullable()->default('');
            $table->integer('user_registro')->default(0);
            $table->date('subido')->nullable()->default(Carbon::now());
            $table->timestamps();
            $table->integer('estado')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('historial_chats', function (Blueprint $table) {
            //
        });
    }
}
