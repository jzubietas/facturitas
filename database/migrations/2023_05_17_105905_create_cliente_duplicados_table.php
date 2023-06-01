<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteDuplicadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_duplicados', function (Blueprint $table) {
            $table->id();
            $table->string('correlativo',20)->nullable()->default('');
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('user_identificador',255)->nullable()->default('');
            $table->string('user_clavepedido',255)->nullable()->default('');
            $table->string('nombre',255)->nullable()->default('');
            $table->string('icelular',1)->nullable()->default('');
            $table->integer('celular')->nullable()->default(0);
            $table->integer('tipo')->default(0);
            $table->string('provincia',255)->nullable()->default('');
            $table->string('distrito',255)->nullable()->default('');
            $table->string('direccion',255)->nullable()->default('');
            $table->string('referencia',255)->nullable()->default('');
            $table->string('dni',255)->nullable()->default('');
            $table->decimal('saldo',10,2)->nullable()->default(0.00);
            $table->integer('deuda')->nullable()->default(0);
            $table->integer('pidio')->nullable()->default(0);
            $table->integer('estado')->nullable()->default(0);
            $table->timestamps();
            $table->integer('crea_temporal')->nullable()->default(0);
            $table->integer('activado_tiempo')->nullable()->default(0);
            $table->integer('activado_pedido')->nullable()->default(0);
            $table->datetime('temporal_update')->nullable()->default(Carbon::now());
            $table->string('situacion')->nullable()->default('');

            $table->integer('congelado')->nullable()->default(0);
            $table->string('sust_congelado',255)->nullable()->default('');
            $table->string('sust_otro_congelado',255)->nullable()->default('');
            $table->integer('user_congelacion_id')->nullable()->default(0);
            $table->string('responsable_congelacion',255)->nullable()->default('');

            $table->integer('bloqueado')->nullable()->default(0);
            $table->string('sust_bloqueado',255)->nullable()->default('');
            $table->string('sust_otro_bloqueado',255)->nullable()->default('');

            $table->integer('user_bloqueado_id')->nullable()->default(0);
            $table->string('responsable_bloqueo',255)->nullable()->default('');

            $table->string('motivo_anulacion',255)->nullable()->default('');
            $table->string('responsable_anulacion',255)->nullable()->default('');
            $table->integer('user_anulacion_id')->nullable()->default(0);

            $table->datetime('fecha_anulacion')->nullable()->default(Carbon::now());
            $table->string('path_adjunto_anular',255)->nullable()->default('');
            $table->string('path_adjunto_anular_disk',255)->nullable()->default('');

            $table->string('agenda',255)->nullable()->default(0);
            $table->datetime('fecha_ultimopedido')->nullable()->default(Carbon::now());
            $table->string('codigo_ultimopedido',255)->nullable()->default('');

            $table->integer('pago_ultimopedido')->nullable()->default(0);
            $table->integer('pagado_ultimopedido')->nullable()->default(0);

            $table->decimal('fsb_porcentaje',2,1)->nullable()->default(0.0);
            $table->decimal('fcb_porcentaje',2,1)->nullable()->default(0.0);
            $table->decimal('esb_porcentaje',2,1)->nullable()->default(0.0);
            $table->decimal('ecb_porcentaje',2,1)->nullable()->default(0.0);

            $table->integer('llamado')->nullable()->default(0);
            $table->string('asesor_llamado',255)->nullable()->default('');
            $table->integer('user_llamado')->nullable()->default(0);
            $table->datetime('fecha_llamado')->nullable()->default(Carbon::now());

            $table->integer('chateado')->nullable()->default(0);
            $table->string('asesor_chateado',255)->nullable()->default('');
            $table->integer('user_chateado')->nullable()->default(0);
            $table->datetime('fecha_chateado')->nullable()->default(Carbon::now());

            $table->integer('total_llamadas')->nullable()->default(0);
            $table->integer('total_chats')->nullable()->default(0);
            $table->integer('grupo_publicidad')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cliente_duplicados');
    }
}
