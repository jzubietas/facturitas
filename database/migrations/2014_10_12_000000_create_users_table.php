<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->integer('estado');
            $table->string('rol');
            $table->string('supervisor')->nullable();//asignar supervisor
            $table->string('operario')->nullable();//asignar operario
            $table->string('jefe')->nullable();//asignar operario
            $table->string('identificador')->nullable();//identificador            
            $table->string('meta_pedido')->nullable();//meta de pedidos por mes
            $table->string('meta_cobro')->nullable();//meta de cobros por mes
            $table->integer('celular')->nullable();     
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->string('direccion')->nullable();
            $table->string('referencia')->nullable(); 
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
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
        Schema::dropIfExists('users');
    }
}
