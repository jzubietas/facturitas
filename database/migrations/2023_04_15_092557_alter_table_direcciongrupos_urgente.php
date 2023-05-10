<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDirecciongruposUrgente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direccion_grupos', function (Blueprint $table) {
            if(!Schema::hasColumn('direccion_grupos','urgente'))
            {
                $table->integer('urgente')->nullable()->default(0)->after('correlativo');
            }
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
