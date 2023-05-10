<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDireccionGrupoAddTextCodigosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direccion_grupos', function (Blueprint $table) {
            $keys = [
                'codigos',
                'producto',
                'direccion',
                'referencia',
                'observacion',
                'cantidad',
            ];
            foreach ($keys as $key) {
                $table->text($key)->nullable()->change();
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
