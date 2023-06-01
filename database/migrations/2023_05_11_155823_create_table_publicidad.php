<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePublicidad extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publicidad', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default('');
            $table->string('email')->nullable()->default('');
            $table->date('cargado')->nullable()->default('1900-01-01');
            $table->integer('total')->default(0);
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
        Schema::dropIfExists('table_publicidad');
    }
}
