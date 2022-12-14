<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('a_pedido', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
        });

        Schema::create('abc', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id')->default(0);
        });

        Schema::create('actu_pedido', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
        });

        Schema::create('b_pedido', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('estado')->nullable();
            $table->decimal('total', 10)->nullable();
            $table->decimal('saldo', 10)->nullable();
        });

        Schema::create('c_pedido', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('estado')->nullable();
            $table->decimal('total', 10)->nullable();
            $table->decimal('saldo', 10)->nullable();
            $table->decimal('total_pago', 11)->nullable();
        });

        Schema::create('clientes_2021_11', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2021_12', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_01', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_02', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_03', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_04', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_05', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_06', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_07', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_08', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_09', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_10', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('clientes_2022_11', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id')->default(0);
        });

        Schema::create('codigos_fernandez', function (Blueprint $table) {

            $table->string('codigo', 100)->nullable();
        });

        Schema::create('completar_atendidopor_pedidos', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
        });

        Schema::create('completar_atendidopor_pedidos_userid', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('completar_atendidopor_pedidos_userid_operario', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('operario', 2)->nullable();
        });

        Schema::create('cron_devueltos', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('destino', 100)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->date('fecha_nueva')->nullable();
            $table->dateTime('hoy');
            $table->bigInteger('dias_transcurridos')->nullable();
        });

        Schema::create('cron_devueltos_b', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('destino', 100)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->date('fecha_nueva')->nullable();
            $table->dateTime('hoy');
            $table->bigInteger('dias_transcurridos')->nullable();
        });

        Schema::create('cuenta_bancarias', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('numero')->nullable();
            $table->string('tipo')->nullable();
            $table->string('titular')->nullable();
            $table->string('banco')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('detalle_pedidos_atendido', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('operario', 2)->nullable();
            $table->string('name_operario')->nullable();
        });

        Schema::create('detallepedidoidvalidar', function (Blueprint $table) {

            $table->string('pedido_id', 100)->nullable();
        });

        Schema::create('deuda_nodeuda', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->integer('celular');
            $table->bigInteger('pedidos_mes_deuda')->nullable();
            $table->bigInteger('pedidos_mes_deuda_antes')->nullable();
        });

        Schema::create('direc_table', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('destino', 100)->nullable();
            $table->string('distribucion', 100)->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
            $table->bigInteger('lima')->nullable();
            $table->bigInteger('provincia')->nullable();
        });

        Schema::create('direccion_envioc', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->integer('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('direccion_grupos', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('destino', 100)->nullable();
            $table->string('distribucion', 100)->nullable();
            $table->string('condicion_envio', 100)->nullable();
            $table->string('subcondicion_envio', 100)->nullable();
            $table->string('condicion_sobre', 100)->nullable();
            $table->string('foto1', 100)->nullable();
            $table->string('foto2', 100)->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->string('atendido_por', 100)->nullable();
            $table->integer('atendido_por_id')->nullable();
            $table->string('nombre_cliente', 100)->nullable();
            $table->string('celular_cliente', 100)->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('direcciongrupoupdate', function (Blueprint $table) {

            $table->integer('direcciongrupo')->nullable();
        });

        Schema::create('entidad_bancarias', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('nombre')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('envios_fix', function (Blueprint $table) {

            $table->string('codigos', 100)->nullable();
        });

        Schema::create('estado_pedidos', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->nullable();
            $table->string('nombre')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('grupo_detalle_pagos', function (Blueprint $table) {

            $table->unsignedBigInteger('pago_id');
            $table->integer('estado');
        });

        Schema::create('grupo_pagos_org', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->decimal('total_pagado', 10)->nullable();
            $table->decimal('sumas_detalle_pagos', 32)->nullable();
            $table->decimal('sumas_pago_pedidos', 32)->nullable();
            $table->decimal('sumas_pago_pedidos_0', 32)->nullable();
        });

        Schema::create('list_pedidos_a', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id')->default(0);
        });

        Schema::create('list_pedidos_b', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id')->default(0);
            $table->string('tipo_banca')->nullable();
            $table->integer('estado')->nullable();
        });

        Schema::create('list_pedidos_c', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id')->default(0);
            $table->string('tipo_banca')->nullable();
            $table->integer('estado')->nullable();
        });

        Schema::create('list_pedidos_d', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id')->default(0);
        });

        Schema::create('listado_clientes', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->string('nombre')->nullable();
            $table->string('icelular', 1)->nullable();
            $table->integer('celular');
            $table->integer('tipo')->nullable();
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->string('direccion')->nullable();
            $table->string('referencia')->nullable();
            $table->string('dni')->nullable();
            $table->decimal('saldo', 10)->nullable();
            $table->integer('deuda')->nullable();
            $table->integer('pidio')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->integer('crea_temporal')->nullable();
            $table->integer('activado_tiempo')->nullable();
            $table->integer('activado_pedido')->nullable();
            $table->timestamp('temporal_update')->nullable();
            $table->timestamp('max_creado_pedido')->nullable();
        });

        Schema::create('listado_pedidos_clientes', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('nombre')->nullable();
            $table->bigInteger('a_2021_11')->nullable();
            $table->bigInteger('a_2021_12')->nullable();
            $table->bigInteger('a_2022_01')->nullable();
            $table->bigInteger('a_2022_02')->nullable();
            $table->bigInteger('a_2022_03')->nullable();
            $table->bigInteger('a_2022_04')->nullable();
            $table->bigInteger('a_2022_05')->nullable();
            $table->bigInteger('a_2022_06')->nullable();
            $table->bigInteger('a_2022_07')->nullable();
            $table->bigInteger('a_2022_08')->nullable();
            $table->bigInteger('a_2022_09')->nullable();
            $table->bigInteger('a_2022_10')->nullable();
            $table->bigInteger('a_2022_11')->nullable();
        });

        Schema::create('listado_resultados', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0)->primary();
            $table->string('nombre')->nullable();
            $table->bigInteger('a_2021_11')->nullable();
            $table->string('s_2021_11');
            $table->bigInteger('a_2021_12')->nullable();
            $table->string('s_2021_12');
            $table->bigInteger('a_2022_01')->nullable();
            $table->string('s_2022_01');
            $table->bigInteger('a_2022_02')->nullable();
            $table->string('s_2022_02');
            $table->bigInteger('a_2022_03')->nullable();
            $table->string('s_2022_03');
            $table->bigInteger('a_2022_04')->nullable();
            $table->string('s_2022_04');
            $table->bigInteger('a_2022_05')->nullable();
            $table->string('s_2022_05');
            $table->bigInteger('a_2022_06')->nullable();
            $table->string('s_2022_06');
            $table->bigInteger('a_2022_07')->nullable();
            $table->string('s_2022_07');
            $table->bigInteger('a_2022_08')->nullable();
            $table->string('s_2022_08');
            $table->bigInteger('a_2022_09')->nullable();
            $table->string('s_2022_09');
            $table->bigInteger('a_2022_10')->nullable();
            $table->string('s_2022_10');
            $table->bigInteger('a_2022_11')->nullable();
            $table->string('s_2022_11');
        });

        Schema::create('padronruc', function (Blueprint $table) {

            $table->string('RUC', 1000)->nullable();
            $table->string('Nombre', 1000)->nullable();
            $table->string('ESTADO DEL CONTRIBUYENTE', 50)->nullable();
        });

        Schema::create('pedido_app', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('pedidos_temp', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('envio')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->string('condicion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
            $table->bigInteger('cant_abono')->nullable();
        });

        Schema::create('pedidosporrevisar', function (Blueprint $table) {

            $table->string('item', 100)->nullable();
            $table->string('ID', 100)->nullable();
            $table->string('PEDIDO', 100)->nullable();
            $table->string('CLIENTECELULAR', 100)->nullable();
            $table->string('NOMBRECLIENTE', 100)->nullable();
            $table->string('RAZONSOCIAL', 100)->nullable();
            $table->string('ASESOR', 100)->nullable();
            $table->string('FECHAREGISTRO', 100)->nullable();
            $table->string('IMPORTE', 100)->nullable();
            $table->string('TIPO', 100)->nullable();
            $table->string('PORCENTAJE', 100)->nullable();
            $table->string('COURIER', 100)->nullable();
            $table->string('TOTAL', 100)->nullable();
            $table->string('DIFERENCIA', 100)->nullable();
            $table->string('ESTADOPEDIDO', 100)->nullable();
            $table->string('ESTADOPAGO', 100)->nullable();
            $table->string('ESTADOENVIO', 100)->nullable();
            $table->string('FECHAMODIFICADO', 100)->nullable();
            $table->string('USUARIOMODIFICADO', 100)->nullable();
        });

        Schema::create('situacioncliente', function (Blueprint $table) {

            $table->bigIncrements('int');
            $table->string('anio', 4)->nullable();
            $table->string('mes', 100)->nullable();
            $table->unsignedBigInteger('cliente')->nullable();
            $table->string('situacion', 100)->nullable();
        });

        Schema::create('tabla_arreglo_envios_lima', function (Blueprint $table) {

            $table->string('codigos', 100)->nullable();
        });

        Schema::create('tabla_arreglo_envios_provincia', function (Blueprint $table) {

            $table->string('codigos', 100)->nullable();
        });

        Schema::create('temp_1', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('envio')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->string('condicion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
        });

        Schema::create('temp_2', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('envio')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->string('condicion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
            $table->string('contador', 8);
        });

        Schema::create('temp_3', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('envio')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->string('condicion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
            $table->string('contador', 8);
            $table->string('contador2', 9);
        });

        Schema::create('temp_direcciongrupo', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('envio')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->string('condicion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->string('foto1')->nullable();
            $table->string('foto2')->nullable();
            $table->unsignedBigInteger('pedido_id')->nullable();
            $table->string('codigo_pedido')->nullable();
        });

        Schema::create('temp_pagos_up', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->date('fecha')->nullable();
        });

        Schema::create('temp_pagos_up_b', function (Blueprint $table) {

            $table->unsignedBigInteger('pago_id')->default(0);
        });

        Schema::create('temporal', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->integer('estado_cabecera')->nullable();
            $table->integer('estado_detalle');
        });

        Schema::create('temporal2', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->integer('estado_cabecera')->nullable();
            $table->integer('estado_detalle');
        });

        Schema::create('temporal_a', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->bigInteger('porcentajes')->nullable();
        });

        Schema::create('temporal_arreglar', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('envio')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->string('condicion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
            $table->integer('direcciongrupo');
            $table->integer('direcionenvio');
        });

        Schema::create('temporal_arreglar_provincia', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->integer('envio')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->string('condicion')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
            $table->integer('direcciongrupo');
            $table->integer('direcionenvio');
        });

        Schema::create('temporal_detalle_pagos', function (Blueprint $table) {

            $table->string('identificador')->nullable();
            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('pago_id');
            $table->decimal('monto', 10);
            $table->string('banco')->nullable();
            $table->string('imagen')->nullable();
            $table->date('fecha')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('titular')->nullable();
            $table->date('fecha_deposito')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('temporal_pagos', function (Blueprint $table) {

            $table->string('identificador')->nullable();
            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cliente_id');
            $table->decimal('total_cobro', 10)->nullable();
            $table->decimal('total_pagado', 10)->nullable();
            $table->string('observacion')->nullable();
            $table->string('condicion')->nullable();
            $table->string('notificacion')->nullable();
            $table->decimal('saldo', 10)->nullable();
            $table->decimal('diferencia', 10)->nullable();
            $table->date('fecha_aprobacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('tipo_movimientos', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('descripcion', 100)->nullable();
            $table->string('banco', 100)->nullable();
        });

        Schema::create('titulares', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('nombre')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });
        Schema::table('pedidos', function (Blueprint $table) {
            $table->integer('condicion')->nullable()->change();
            $table->integer('condicion_envio')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('titulares');

        Schema::dropIfExists('tipo_movimientos');

        Schema::dropIfExists('temporal_pagos');

        Schema::dropIfExists('temporal_detalle_pagos');

        Schema::dropIfExists('temporal_arreglar_provincia');

        Schema::dropIfExists('temporal_arreglar');

        Schema::dropIfExists('temporal_a');

        Schema::dropIfExists('temporal2');

        Schema::dropIfExists('temporal');

        Schema::dropIfExists('temp_pagos_up_b');

        Schema::dropIfExists('temp_pagos_up');

        Schema::dropIfExists('temp_direcciongrupo');

        Schema::dropIfExists('temp_3');

        Schema::dropIfExists('temp_2');

        Schema::dropIfExists('temp_1');

        Schema::dropIfExists('tabla_arreglo_envios_provincia');

        Schema::dropIfExists('tabla_arreglo_envios_lima');

        Schema::dropIfExists('situacioncliente');

        Schema::dropIfExists('pedidosporrevisar');

        Schema::dropIfExists('pedidos_temp');

        Schema::dropIfExists('pedido_app');

        Schema::dropIfExists('padronruc');

        Schema::dropIfExists('listado_resultados');

        Schema::dropIfExists('listado_pedidos_clientes');

        Schema::dropIfExists('listado_clientes');

        Schema::dropIfExists('list_pedidos_d');

        Schema::dropIfExists('list_pedidos_c');

        Schema::dropIfExists('list_pedidos_b');

        Schema::dropIfExists('list_pedidos_a');

        Schema::dropIfExists('grupo_pagos_org');

        Schema::dropIfExists('grupo_detalle_pagos');

        Schema::dropIfExists('estado_pedidos');

        Schema::dropIfExists('envios_fix');

        Schema::dropIfExists('entidad_bancarias');

        Schema::dropIfExists('direcciongrupoupdate');

        Schema::dropIfExists('direccion_grupos');

        Schema::dropIfExists('direccion_envioc');

        Schema::dropIfExists('direc_table');

        Schema::dropIfExists('deuda_nodeuda');

        Schema::dropIfExists('detallepedidoidvalidar');

        Schema::dropIfExists('detalle_pedidos_atendido');

        Schema::dropIfExists('cuenta_bancarias');

        Schema::dropIfExists('cron_devueltos_b');

        Schema::dropIfExists('cron_devueltos');

        Schema::dropIfExists('completar_atendidopor_pedidos_userid_operario');

        Schema::dropIfExists('completar_atendidopor_pedidos_userid');

        Schema::dropIfExists('completar_atendidopor_pedidos');

        Schema::dropIfExists('codigos_fernandez');

        Schema::dropIfExists('clientes_2022_11');

        Schema::dropIfExists('clientes_2022_10');

        Schema::dropIfExists('clientes_2022_09');

        Schema::dropIfExists('clientes_2022_08');

        Schema::dropIfExists('clientes_2022_07');

        Schema::dropIfExists('clientes_2022_06');

        Schema::dropIfExists('clientes_2022_05');

        Schema::dropIfExists('clientes_2022_04');

        Schema::dropIfExists('clientes_2022_03');

        Schema::dropIfExists('clientes_2022_02');

        Schema::dropIfExists('clientes_2022_01');

        Schema::dropIfExists('clientes_2021_12');

        Schema::dropIfExists('clientes_2021_11');

        Schema::dropIfExists('c_pedido');

        Schema::dropIfExists('b_pedido');

        Schema::dropIfExists('actu_pedido');

        Schema::dropIfExists('abc');

        Schema::dropIfExists('a_pedido');
    }
};
