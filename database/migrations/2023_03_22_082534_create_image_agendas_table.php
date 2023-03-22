<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageAgendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_agendas', function (Blueprint $table) {
            $table->id();
            $table->integer('unsigned')->default(0)->nullable()->comment('0 unsigned  1  official');
            $table->integer('event_id')->default(0)->nullable();
            $table->string('filename')->default('')->nullable();
            $table->string('filepath')->default('')->nullable();
            $table->string('filetype')->default('')->nullable();
            $table->integer('status')->default(0)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_agendas');
    }
}
