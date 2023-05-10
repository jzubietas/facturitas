<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriaPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historia_pedidos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pedido_id');
            $table->string('correlativo',7);
            $table->bigInteger('cliente_id');
            $table->bigInteger('user_id');
            $table->string('identificador');
            $table->string('exidentificador');
            $table->string('icelular_asesor');
            $table->string('celular_cliente');
            $table->string('icelular_cliente');
            $table->string('creador');
            $table->integer('pago');
            $table->integer('pagado');
            $table->string('condicion_envio');
            $table->integer('condicion_envio_code');
            $table->dateTime('condicion_envio_at');
            $table->string('codigo');
            $table->string('motivo');
            $table->string('responsable');
            $table->string('modificador');
            $table->integer('estado');
            $table->integer('da_confirmar_descarga');
            $table->integer('estado_sobre');
            $table->integer('estado_consinsobre');
            $table->string('env_destino');
            $table->string('env_distrito');
            $table->string('env_zona');
            $table->string('env_zona_asignada');
            $table->string('env_nombre_cliente_recibe');
            $table->string('env_celular_cliente_recibe');
            $table->string('env_cantidad');
            $table->string('env_direccion');
            $table->string('env_tracking');
            $table->string('env_referencia');
            $table->string('env_numregistro');
            $table->string('env_rotulo');
            $table->string('env_observacion');
            $table->string('env_gmlink');
            $table->string('env_importe');
            $table->string('estado_ruta');
            $table->string('direccion_grupo');
            $table->integer('estado_correccion');
            $table->string('nombre_empresa');
            $table->string('mes');
            $table->integer('anio');
            $table->string('ruc',12);
            $table->decimal('cantidad',18,2);
            $table->string('tipo_banca');
            $table->string('porcentaje');
            $table->string('ft');
            $table->string('courier');
            $table->string('total');
            $table->string('saldo');
            $table->string('descripcion');
            $table->string('nota');
            $table->string('cant_compro');
            $table->string('atendido_por');
            $table->string('atendido_por_id');

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
        Schema::dropIfExists('historia_pedidos');
    }
}
