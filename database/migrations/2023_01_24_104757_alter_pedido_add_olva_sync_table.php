<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidoAddOlvaSyncTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->timestamp('courier_sync_at')->nullable();
            $table->timestamp('courier_failed_sync_at')->nullable();
            $table->boolean('courier_sync_finalized')->nullable();

            $table->string('courier_estado')->nullable();
            $table->json('courier_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
