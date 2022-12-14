<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevolucionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('devolucions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("pago_id");
            $table->unsignedInteger("client_id");
            $table->unsignedInteger("asesor_id")->comment("id usuario asesor");
            $table->string("bank_destino")->nullable();
            $table->string("bank_number")->nullable();
            $table->string("num_operacion")->nullable();
            $table->string("bank_titular")->nullable();

            $table->float("amount")->comment("monto a devolver");
            $table->integer("status")->default(\App\Models\Devolucion::PENDIENTE);
            $table->string("voucher_disk")->nullable();
            $table->text("voucher_path")->nullable();
            $table->timestamp("returned_at")->nullable();
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
        Schema::dropIfExists('devolucions');
    }
}
