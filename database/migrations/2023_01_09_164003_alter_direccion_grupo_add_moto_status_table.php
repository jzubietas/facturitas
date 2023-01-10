<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDireccionGrupoAddMotoStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direccion_grupos', function (Blueprint $table) {
            $table->integer('motorizado_status')->default('0')->comment('[0]: general, [1]: observado, [2]: no_contesto');
            $table->text('motorizado_sustento_text')->nullable()->after('motorizado_status');
            $table->string('motorizado_sustento_foto',255)->nullable()->after('motorizado_sustento_text');
        });

        Schema::create('pedido_motorizado_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('direccion_grupo_id');
            $table->unsignedBigInteger('pedido_grupo_id')->nullable();
            $table->integer('status')->default('0')->comment('[0]: general, [1]: observado, [2]: no_contesto');
            $table->text('sustento_text')->nullable();
            $table->string('sustento_foto',255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
