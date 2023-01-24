<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierRegistrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_registros', function (Blueprint $table) {
            $table->id();
            $table->string('courier_registro',250)->nullable();
            $table->string('adjunto',250)->nullable();
            $table->unsignedBigInteger('user_created')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('user_updated')->default(0);
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('user_deleted')->default(0);
            $table->timestamp('deleted_at')->nullable();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courier_registros');
    }
}
