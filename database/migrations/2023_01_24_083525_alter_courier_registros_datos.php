<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCourierRegistrosDatos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //relacionado
        //rel_direcciongrupo
        //rel_fecha
        //rel_importe
        //rel_tracking
        //rel_userid
        Schema::table('courier_registros', function (Blueprint $table) {
            $table->integer('relacionado')->default('0')->after('status')->comment('es la columna para etiquetar status');
            $table->unsignedBigInteger('rel_direcciongrupo')->nullable()->after('relacionado');
            $table->timestamp('rel_fechadp')->nullable()->after('rel_direcciongrupo');
            $table->decimal('rel_importe',10,2)->nullable()->after('rel_fechadp');
            $table->string('rel_tracking',12)->nullable()->after('rel_importe');
            $table->integer('rel_userid')->default('0')->after('rel_tracking');
            $table->timestamp('rel_fecharel')->nullable()->after('rel_userid')->comment('es la columna para etiquetar status');;
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
