<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableClientesColsCongeladosustento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if(!Schema::hasColumn('clientes','sust_congelado'))
            {
                $table->string('sust_congelado')->nullable()->default('')->coment('sustento congelado')->after('congelado');
                $table->string('sust_otro_congelado')->nullable()->default('')->coment('sustento otro congelado')->after('sust_congelado');
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
        Schema::table('clientes', function (Blueprint $table) {
            //
        });
    }
}
