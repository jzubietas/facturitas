<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCallAtentions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('call_atentions', function (Blueprint $table) {
            //
            if(!Schema::hasColumn('call_atentions','user_id'))
            {
                $table->integer('user_id')->nullable()->default(0)->after('id');
            }
            if(!Schema::hasColumn('call_atentions','user_identificador'))
            {
                $table->string('user_identificador')->nullable()->default('')->after('user_id');
            }
            if(!Schema::hasColumn('call_atentions','accion'))
            {
                $table->string('accion')->nullable()->default('')->after('user_identificador');
            }
            if(!Schema::hasColumn('call_atentions','responsable'))
            {
                $table->integer('responsable')->nullable()->default(0)->after('accion');
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
        Schema::table('call_atentions', function (Blueprint $table) {
            //
        });
    }
}
