<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //

        Schema::table('metas',function (Blueprint $table){
            if(!Schema::hasColumn('metas','user_id'))
            {
                $table->unsignedBigInteger('user_id')->nullable()->comment('')->after('rol');
                $table->string('email',255)->nullable()->comment('')->after('user_id');
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
        //
    }
}
