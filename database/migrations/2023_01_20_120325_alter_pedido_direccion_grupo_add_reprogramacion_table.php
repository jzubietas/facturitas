<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidoDireccionGrupoAddReprogramacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direccion_grupos', function (Blueprint $table) {
            $table->timestamp('reprogramacion_at')
                ->nullable()
                ->comment('Reprogramar fecha salida');


            $table->unsignedBigInteger('reprogramacion_solicitud_user_id')
                ->nullable()
                ->comment('Usuario que solicita la reprogramacion');
            $table->timestamp('reprogramacion_solicitud_at')
                ->nullable()
                ->comment('Fecha que solicito la reprogramacion');


            $table->unsignedBigInteger('reprogramacion_accept_user_id')
                ->nullable()
                ->comment('Usuario que acepta la reprogramacion');
            $table->timestamp('reprogramacion_accept_at')
                ->nullable()
                ->comment('Fecha que acepto la reprogramacion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('direccion_grupos', function (Blueprint $table) {
            //
        });
    }
}
