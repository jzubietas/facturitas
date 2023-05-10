<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corrections_tables', function (Blueprint $table) {
            $table->id();
            $table->string('type',30)->nullable();
            $table->string('code',30)->nullable();
            $table->string('ruc',11)->nullable();
            $table->string('razon_social',255)->nullable();
            $table->unsignedBigInteger('asesor_id')->nullable();
            $table->string('asesor_identify')->nullable();
            $table->dateTime('fecha_correccion')->nullable();
            $table->string('motivo')->nullable();
            $table->integer('adjuntos')->nullable();
            //$table->integer('facturas')->nullable();
            $table->string('detalle')->nullable();
            $table->boolean('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('attach_corrections', function (Blueprint $table) {
            $table->id();
            $table->integer('correction_id')->nullable();
            $table->string('type')->nullable()->comment('adjuntos / facturas');
            $table->string('name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->boolean('estado')->nullable();
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
        Schema::dropIfExists('corrections_tables');
    }
}
