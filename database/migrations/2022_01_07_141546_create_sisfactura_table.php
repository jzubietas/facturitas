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
        Schema::create('a123', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->integer('dg1')->nullable();
            $table->bigInteger('conta')->nullable();
        });

        Schema::create('aa_temp', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->integer('direccion_grupo')->nullable();
            $table->unsignedBigInteger('direcciongrupo')->nullable()->default(0);
        });

        Schema::create('asd', function (Blueprint $table) {

            $table->integer('id')->nullable();
            $table->bigInteger('conta');
            $table->bigInteger('separaciones')->nullable();
        });

        Schema::create('aux_clientes', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('aux_dg', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('distrito', 100)->nullable()->comment('campo agregado 09-12-22');
            $table->string('distrito_aux')->nullable();
            $table->string('direccion_aux')->nullable();
            $table->string('referencia_aux')->nullable();
            $table->string('observacion_aux')->nullable();
            $table->string('nombre_aux')->nullable();
            $table->integer('celular_aux')->nullable();
            $table->string('nombrecliente_aux')->nullable();
            $table->integer('celularcliente_aux')->nullable();
            $table->string('icelularcliente_aux', 1)->nullable();
            $table->string('identificadorasesor_aux')->nullable();
        });

        Schema::create('aux_dgp', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('distrito', 100)->nullable()->comment('campo agregado 09-12-22');
            $table->char('distrito_aux', 0);
            $table->string('direccion_aux')->nullable();
            $table->string('referencia_aux')->nullable();
            $table->string('observacion_aux')->nullable();
            $table->string('nombre_aux', 4);
            $table->string('celular_aux', 4);
            $table->integer('cantidad_aux')->nullable();
            $table->decimal('importe_aux', 10)->nullable();
            $table->string('nombrecliente_aux')->nullable();
            $table->integer('celularcliente_aux')->nullable();
            $table->string('icelularcliente_aux', 1)->nullable();
            $table->string('identificadorasesor_aux')->nullable();
        });

        Schema::create('cliente_listado_corregir', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::create('clientes', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('nombre')->nullable();
            $table->string('icelular', 1)->nullable();
            $table->integer('celular')->unique();
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
            $table->string('situacion')->nullable();
            $table->string('motivo_anulacion', 1000)->nullable();
            $table->string('responsable_anulacion')->nullable();
            $table->integer('user_anulacion_id')->nullable();
            $table->timestamp('fecha_anulacion')->nullable();
            $table->string('path_adjunto_anular')->nullable();
            $table->string('path_adjunto_anular_disk')->nullable();
        });

        Schema::create('clientes_compilar', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->string('identificador')->nullable();
        });

        Schema::create('clientes_respaldo', function (Blueprint $table) {

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
            $table->string('situacion')->nullable();
        });

        Schema::create('codigo_val', function (Blueprint $table) {

            $table->string('codigo')->nullable();
            $table->string('codigo_val')->nullable();
        });

        Schema::create('codigo_val_no', function (Blueprint $table) {

            $table->string('codigo')->nullable();
        });

        Schema::create('codigos_calculo', function (Blueprint $table) {

            $table->string('codigo')->nullable();
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

        Schema::create('conta_pedidos', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->bigInteger('s_2021_11')->nullable();
            $table->bigInteger('s_2021_12')->nullable();
            $table->bigInteger('s_2022_01')->nullable();
            $table->bigInteger('s_2022_02')->nullable();
            $table->bigInteger('s_2022_03')->nullable();
            $table->bigInteger('s_2022_04')->nullable();
            $table->bigInteger('s_2022_05')->nullable();
            $table->bigInteger('s_2022_06')->nullable();
            $table->bigInteger('s_2022_07')->nullable();
            $table->bigInteger('s_2022_08')->nullable();
            $table->bigInteger('s_2022_09')->nullable();
            $table->bigInteger('s_2022_10')->nullable();
            $table->bigInteger('s_2022_11')->nullable();
            $table->bigInteger('s_2022_12')->nullable();
            $table->bigInteger('s_2023_01')->nullable();
        });

        Schema::create('conta_pedidos_a', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->bigInteger('s_2021_11')->nullable();
            $table->bigInteger('s_2021_12')->nullable();
            $table->bigInteger('s_2022_01')->nullable();
            $table->bigInteger('s_2022_02')->nullable();
            $table->bigInteger('s_2022_03')->nullable();
            $table->bigInteger('s_2022_04')->nullable();
            $table->bigInteger('s_2022_05')->nullable();
            $table->bigInteger('s_2022_06')->nullable();
            $table->bigInteger('s_2022_07')->nullable();
            $table->bigInteger('s_2022_08')->nullable();
            $table->bigInteger('s_2022_09')->nullable();
            $table->bigInteger('s_2022_10')->nullable();
            $table->bigInteger('s_2022_11')->nullable();
            $table->bigInteger('s_2022_12')->nullable();
        });

        Schema::create('corregir_ahora', function (Blueprint $table) {

            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_pedidos');
            $table->unsignedBigInteger('user_clientes')->nullable();
        });

        Schema::create('corregir_rucs', function (Blueprint $table) {

            $table->string('ruc');
            $table->integer('cliente_id')->nullable();
            $table->string('relacion_ruc', 15)->nullable();
        });

        Schema::create('count_pedidos_cliente', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->bigInteger('pedidos_2023_01')->nullable();
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

            $table->id();
            $table->string('numero')->nullable();
            $table->string('tipo')->nullable();
            $table->string('titular')->nullable();
            $table->string('banco')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('cuenta_dg', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->integer('direccion_grupo')->nullable();
            $table->bigInteger('direccion_grupo_cuenta')->nullable();
        });

        Schema::create('departamentos', function (Blueprint $table) {

            $table->id();
            $table->string('departamento')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_pagos', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('pago_id')->index();
            $table->decimal('monto', 10);
            $table->string('banco')->nullable();
            $table->string('bancop')->nullable();
            $table->string('obanco')->nullable();
            $table->string('imagen')->nullable();
            $table->date('fecha')->nullable();
            $table->string('cuenta')->nullable();
            $table->string('titular')->nullable();
            $table->date('fecha_deposito')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('detalle_pedidos', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('pedido_id')->index();
            $table->string('codigo');
            $table->string('nombre_empresa');
            $table->string('mes');
            $table->string('anio')->nullable();
            $table->string('ruc');
            $table->decimal('cantidad', 10);
            $table->string('adjunto')->nullable();
            $table->string('tipo_banca');
            $table->decimal('porcentaje', 2, 1);
            $table->decimal('ft', 10);
            $table->decimal('courier', 10);
            $table->decimal('total', 10);
            $table->decimal('saldo', 10)->nullable();
            $table->string('descripcion')->nullable();
            $table->string('nota')->nullable();
            $table->string('envio_doc')->nullable();
            $table->dateTime('fecha_envio_doc')->nullable();
            $table->integer('cant_compro')->default(0);
            $table->date('fecha_envio_doc_fis')->nullable();
            $table->string('foto1')->nullable();
            $table->string('foto2')->nullable();
            $table->string('atendido_por', 1000)->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->unsignedBigInteger('atendido_por_id')->nullable();
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

        Schema::create('devolucions', function (Blueprint $table) {

            $table->id();
            $table->unsignedInteger('pago_id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('asesor_id')->comment('id usuario asesor');
            $table->string('bank_destino')->nullable();
            $table->string('bank_number')->nullable();
            $table->string('num_operacion')->nullable();
            $table->string('bank_titular')->nullable();
            $table->double('amount', 8, 2)->comment('monto a devolver');
            $table->integer('status')->default(1);
            $table->string('voucher_disk')->nullable();
            $table->text('voucher_path')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
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

            $table->id();
            $table->integer('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('direccion_envios', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('cliente_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('departamento')->nullable();
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->string('direccion')->nullable();
            $table->string('referencia')->nullable();
            $table->string('nombre')->nullable();
            $table->integer('celular')->nullable();
            $table->integer('direcciongrupo')->nullable();
            $table->integer('cantidad')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado')->nullable();
            $table->boolean('salvado')->nullable()->default(false);
            $table->timestamps();
        });

        Schema::create('direccion_grupo_calcular', function (Blueprint $table) {

            $table->integer('direccion_grupo')->nullable();
            $table->bigInteger('conta');
        });

        Schema::create('direccion_grupo_calcular_index', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->bigInteger('separaciones')->nullable();
        });

        Schema::create('direccion_grupos', function (Blueprint $table) {

            $table->id();
            $table->string('correlativo', 20)->nullable()->comment('nuevo codigo correlativo creado para las busquedas en datatables envios');
            $table->string('destino', 100)->nullable();
            $table->string('distribucion', 100)->nullable();
            $table->integer('condicion_envio_code')->nullable();
            $table->string('condicion_envio', 100)->nullable();
            $table->string('subcondicion_envio', 100)->nullable();
            $table->string('condicion_sobre', 100)->nullable();
            $table->string('foto1', 100)->nullable();
            $table->string('foto2', 100)->nullable();
            $table->string('foto3', 100)->nullable();
            $table->timestamp('fecha_recepcion')->nullable();
            $table->string('atendido_por', 100)->nullable();
            $table->integer('atendido_por_id')->nullable();
            $table->string('nombre_cliente', 100)->nullable();
            $table->string('celular_cliente', 100)->nullable();
            $table->string('icelular_cliente', 100)->nullable();
            $table->integer('estado')->nullable();
            $table->unsignedBigInteger('motorizado_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('codigos')->nullable();
            $table->text('producto')->nullable();
            $table->string('identificador')->nullable()->comment('campo agregado 09-12-22');
            $table->string('celular', 30)->nullable()->comment('campo agregado 09-12-22');
            $table->string('nombre', 100)->nullable()->comment('campo agregado 09-12-22');
            $table->timestamp('fecha')->nullable()->comment('campo agregado 09-12-22');
            $table->integer('cantidad')->nullable()->comment('campo agregado 09-12-22');
            $table->decimal('importe', 10)->nullable()->default(0);
            $table->string('direccion')->nullable()->comment('campo agregado 09-12-22 tracking');
            $table->string('referencia')->nullable()->comment('campo agregado 09-12-22 numregistro');
            $table->text('observacion')->nullable()->comment('campo agregado 09-12-22  adjunto');
            $table->string('distrito', 100)->nullable()->comment('campo agregado 09-12-22');
            $table->string('destino2', 100)->nullable()->comment('campo agregado 09-12-22');
            $table->integer('pedido_id')->nullable()->comment('campo tentativo para pedidos 16-12-22');
        });

        Schema::create('direccion_pedidos', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('direccion_id')->index();
            $table->unsignedBigInteger('pedido_id')->index();
            $table->string('codigo_pedido')->nullable();
            $table->integer('direcciongrupo')->nullable();
            $table->string('empresa')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('direcciongrupoupdate', function (Blueprint $table) {

            $table->integer('direcciongrupo')->nullable();
        });

        Schema::create('distritos', function (Blueprint $table) {

            $table->id();
            $table->string('distrito')->nullable();
            $table->string('provincia')->nullable();
            $table->string('zona')->nullable();
            $table->string('sugerencia')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('eee', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->string('destino')->nullable();
            $table->string('env_destino', 250)->nullable()->comment('LIMA PROVINCIA');
            $table->string('env_distrito', 250)->nullable()->comment('LIMA PROVINCIA');
            $table->string('env_direccion', 250)->nullable()->comment('direccion lima provincia');
            $table->string('env_referencia', 250)->nullable();
            $table->string('env_zona', 250)->nullable()->comment('NORTE SUR ESTE OESTE para LIMA PROVINCIA');
            $table->string('env_nombre_cliente_recibe', 250)->nullable()->comment('LIMA o PROVINCIA');
            $table->string('env_celular_cliente_recibe', 250)->nullable()->comment('LIMA');
            $table->integer('direcciongrupo')->nullable();
            $table->string('nuevo_distrito')->nullable();
            $table->string('nuevo_direccion')->nullable();
            $table->string('nuevo_referencia')->nullable();
            $table->string('nuevo_observacion')->nullable();
            $table->string('nuevo_nombre')->nullable();
            $table->integer('nuevo_celular')->nullable();
        });

        Schema::create('empresas_pedidos_old', function (Blueprint $table) {

            $table->string('empresa');
            $table->string('num_ruc');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        Schema::create('entidad_bancarias', function (Blueprint $table) {

            $table->id();
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

        Schema::create('export_ex', function (Blueprint $table) {

            $table->string('ruc');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('fix_tabla', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
        });

        Schema::create('gasto_envios', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('cliente_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('tracking')->nullable();
            $table->string('registro')->nullable();
            $table->string('foto')->nullable();
            $table->integer('cantidad')->nullable();
            $table->decimal('importe', 10);
            $table->integer('direcciongrupo')->nullable();
            $table->integer('estado')->nullable();
            $table->boolean('salvado')->nullable()->default(false);
            $table->timestamps();
        });

        Schema::create('gasto_pedidos', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('gasto_id')->index();
            $table->unsignedBigInteger('pedido_id')->index();
            $table->string('codigo_pedido')->nullable();
            $table->integer('direcciongrupo')->nullable();
            $table->string('empresa')->nullable();
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

        Schema::create('grupo_pedido_items', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('grupo_pedido_id')->index();
            $table->string('razon_social');
            $table->string('codigo');

            $table->unique(['pedido_id', 'grupo_pedido_id']);
        });

        Schema::create('grupo_pedidos', function (Blueprint $table) {

            $table->id();
            $table->string('zona');
            $table->string('provincia');
            $table->string('distrito');
            $table->string('direccion');
            $table->string('referencia')->nullable();
            $table->string('cliente_recibe')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('imagen_atencions', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('pedido_id')->index();
            $table->string('adjunto')->nullable();
            $table->integer('confirm')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('imagen_pedidos', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('pedido_id')->index();
            $table->string('adjunto')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('list_casi_abandono', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
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

        Schema::create('lista_pedidos_migrar', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->integer('cantidad_comprobantes')->nullable();
        });

        Schema::create('listaa', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->bigInteger('counta')->nullable();
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
            $table->bigInteger('a_2022_12')->nullable();
        });

        Schema::create('listado_pedidos_clientes_cc', function (Blueprint $table) {

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
            $table->bigInteger('a_2022_12')->nullable();
            $table->char('s_2022_12', 0);
        });

        Schema::create('listado_resultados', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0)->unique();
            $table->string('user_identificador')->nullable();
            $table->bigInteger('a_2021_11')->nullable()->default(0);
            $table->string('s_2021_11', 21);
            $table->bigInteger('a_2021_12')->nullable()->default(0);
            $table->string('s_2021_12', 21);
            $table->bigInteger('a_2022_01')->nullable()->default(0);
            $table->string('s_2022_01', 21);
            $table->bigInteger('a_2022_02')->nullable()->default(0);
            $table->string('s_2022_02', 21);
            $table->bigInteger('a_2022_03')->nullable()->default(0);
            $table->string('s_2022_03', 21);
            $table->bigInteger('a_2022_04')->nullable()->default(0);
            $table->string('s_2022_04', 21);
            $table->bigInteger('a_2022_05')->nullable()->default(0);
            $table->string('s_2022_05', 21);
            $table->bigInteger('a_2022_06')->nullable()->default(0);
            $table->string('s_2022_06', 21);
            $table->bigInteger('a_2022_07')->nullable()->default(0);
            $table->string('s_2022_07', 21);
            $table->bigInteger('a_2022_08')->nullable()->default(0);
            $table->string('s_2022_08', 21);
            $table->bigInteger('a_2022_09')->nullable()->default(0);
            $table->string('s_2022_09', 21);
            $table->bigInteger('a_2022_10')->nullable()->default(0);
            $table->string('s_2022_10', 21);
            $table->bigInteger('a_2022_11')->nullable()->default(0);
            $table->string('s_2022_11', 21);
            $table->bigInteger('a_2022_12')->nullable()->default(0);
            $table->string('s_2022_12', 21);
            $table->bigInteger('a_2023_01')->nullable()->default(0);
            $table->string('s_2023_01', 21)->nullable();
        });

        Schema::create('listado_revisar_clientes', function (Blueprint $table) {

            $table->string('ruc');
            $table->integer('cliente_id')->nullable();
            $table->integer('celular')->nullable();
            $table->string('relacion_ruc', 15)->nullable();
        });

        Schema::create('miemporal', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->unsignedBigInteger('dg')->nullable();
        });

        Schema::create('migra_dg_pedido', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->string('codigo_pedido')->nullable();
            $table->string('env_destino', 100)->nullable();
            $table->string('env_distrito', 100)->nullable()->comment('campo agregado 09-12-22');
            $table->string('zona', 100)->nullable();
            $table->string('env_nombre_cliente_recibe', 100)->nullable()->comment('campo agregado 09-12-22');
            $table->string('env_celular_cliente_recibe', 30)->nullable()->comment('campo agregado 09-12-22');
            $table->integer('env_cantidad')->nullable()->comment('campo agregado 09-12-22');
            $table->string('env_direccion')->nullable()->comment('campo agregado 09-12-22 tracking');
            $table->string('env_referencia')->nullable()->comment('campo agregado 09-12-22 numregistro');
            $table->text('env_observacion')->nullable()->comment('campo agregado 09-12-22  adjunto');
        });

        Schema::create('migra_dg_pedido_provincia', function (Blueprint $table) {

            $table->unsignedBigInteger('pedido_id');
            $table->string('codigo_pedido')->nullable();
            $table->string('env_destino', 100)->nullable();
            $table->string('env_distrito', 10);
            $table->string('zona', 100)->nullable();
            $table->string('env_nombre_cliente_recibe', 100)->nullable()->comment('campo agregado 09-12-22');
            $table->string('env_celular_cliente_recibe', 30)->nullable()->comment('campo agregado 09-12-22');
            $table->integer('env_cantidad')->nullable()->comment('campo agregado 09-12-22');
            $table->string('env_tracking')->nullable()->comment('campo agregado 09-12-22 tracking');
            $table->string('env_numregistro')->nullable()->comment('campo agregado 09-12-22 numregistro');
            $table->text('env_rotulo')->nullable()->comment('campo agregado 09-12-22  adjunto');
            $table->decimal('env_importe', 10)->nullable()->default(0);
        });

        Schema::create('mitemp', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->string('destino')->nullable();
            $table->string('env_destino', 250)->nullable()->comment('LIMA PROVINCIA');
            $table->string('env_distrito', 250)->nullable()->comment('LIMA PROVINCIA');
            $table->string('env_direccion', 250)->nullable()->comment('direccion lima provincia');
            $table->string('env_referencia', 250)->nullable();
            $table->string('env_zona', 250)->nullable()->comment('NORTE SUR ESTE OESTE para LIMA PROVINCIA');
            $table->string('env_nombre_cliente_recibe', 250)->nullable()->comment('LIMA o PROVINCIA');
            $table->string('env_celular_cliente_recibe', 250)->nullable()->comment('LIMA');
            $table->integer('direcciongrupo')->nullable();
        });

        Schema::create('movimiento_bancarios', function (Blueprint $table) {

            $table->id();
            $table->string('banco')->nullable();
            $table->string('titular')->nullable();
            $table->decimal('importe', 10)->nullable();
            $table->string('tipo')->nullable();
            $table->string('descripcion_otros')->nullable();
            $table->dateTime('fecha')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('detpago')->nullable();
            $table->integer('cabpago')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('nuevo_estado_pedidos', function (Blueprint $table) {

            $table->integer('id', true)->comment('id');
            $table->string('descripcion', 60)->comment('descripcion del estado');
            $table->integer('tipo')->nullable()->comment('1=estado del envio, 2 estado del sobre');
            $table->integer('visible')->nullable()->default(1)->comment('campo para eliminar de forma logica');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('modulo', 60)->nullable();
        });

        Schema::create('pago_pedidos', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('pago_id')->index();
            $table->unsignedBigInteger('pedido_id')->index();
            $table->integer('pagado')->nullable();
            $table->decimal('abono', 10)->nullable()->default(0);
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('pagos', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('cliente_id')->index();
            $table->decimal('total_cobro', 10)->nullable();
            $table->decimal('total_pagado', 10)->nullable();
            $table->text('observacion')->nullable();
            $table->string('condicion')->nullable();
            $table->string('subcondicion')->nullable();
            $table->integer('subcondicion_code')->nullable();
            $table->string('notificacion')->nullable();
            $table->decimal('saldo', 10)->nullable();
            $table->decimal('diferencia', 10)->nullable();
            $table->date('fecha_aprobacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
            $table->integer('condicion_code')->nullable();
        });

        Schema::create('password_resets', function (Blueprint $table) {

            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('pedido_app', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('pedido_clientes', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->integer('periodo_anio')->nullable();
            $table->integer('periodo_mes')->nullable();
            $table->bigInteger('total_pedidos');
        });

        Schema::create('pedido_movimiento_estados', function (Blueprint $table) {

            $table->integer('id', true);
            $table->integer('condicion_envio_code')->nullable();
            $table->timestamp('fecha')->nullable()->useCurrent();
            $table->integer('pedido')->nullable();
            $table->timestamps();
            $table->integer('notificado')->nullable();
        });

        Schema::create('pedidos', function (Blueprint $table) {

            $table->id();
            $table->string('correlativo', 7)->nullable();
            $table->unsignedBigInteger('cliente_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->integer('condicion_envio_code')->nullable();
            $table->string('condicion')->nullable();
            $table->integer('condicion_code')->nullable();
            $table->integer('condicion_int')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->boolean('da_confirmar_descarga')->nullable()->default(false);
            $table->text('sustento_adjunto')->nullable();
            $table->string('path_adjunto_anular')->nullable()->comment('archivo adjunto antes de analizar');
            $table->string('path_adjunto_anular_disk')->nullable()->comment('disk archivo adjunto');
            $table->boolean('pendiente_anulacion')->default(false)->comment('estado para controlar la si esta pendiente de anulacion');
            $table->unsignedInteger('user_anulacion_id')->nullable()->comment('Id del usuario que solicita la anulacion');
            $table->timestamp('fecha_anulacion')->nullable()->comment('Fecha de anulacion');
            $table->timestamp('fecha_anulacion_confirm')->nullable()->comment('Fecha de anulacion confirmada');
            $table->timestamp('fecha_anulacion_denegada')->nullable();
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
            $table->integer('envio')->nullable();
            $table->integer('estado_condicion_envio')->nullable();
            $table->integer('estado_condicion_pedido')->nullable();
            $table->integer('estado_sobre')->nullable()->default(0);
            $table->string('env_destino', 250)->nullable()->comment('LIMA PROVINCIA');
            $table->string('env_distrito', 250)->nullable()->comment('LIMA PROVINCIA');
            $table->string('env_zona', 250)->nullable()->comment('NORTE SUR ESTE OESTE para LIMA PROVINCIA');
            $table->string('env_zona_asignada', 250)->nullable()->comment('ZONA CONFIRMADA PARA ENVIAR A MOTORIZADO');
            $table->string('env_nombre_cliente_recibe', 250)->nullable()->comment('LIMA o PROVINCIA');
            $table->string('env_celular_cliente_recibe', 250)->nullable()->comment('LIMA');
            $table->string('env_cantidad', 250)->nullable();
            $table->string('env_direccion', 250)->nullable()->comment('direccion lima provincia');
            $table->string('env_tracking', 250)->nullable()->comment('tracking provincia');
            $table->string('env_referencia', 250)->nullable();
            $table->string('env_numregistro', 250)->nullable();
            $table->string('env_rotulo', 250)->nullable();
            $table->string('env_observacion', 250)->nullable();
            $table->string('env_importe', 250)->nullable();
            $table->integer('estado_ruta')->nullable();
            $table->integer('direccion_grupo')->nullable();
        });

        Schema::create('pedidos_condicion_poratender', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
        });

        Schema::create('pedidos_respaldo', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('correlativo', 7)->nullable();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->integer('condicion_envio_code')->nullable();
            $table->string('condicion')->nullable();
            $table->integer('condicion_code')->nullable();
            $table->integer('condicion_int')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->boolean('da_confirmar_descarga')->nullable()->default(false);
            $table->string('path_adjunto_anular')->nullable()->comment('archivo adjunto antes de analizar');
            $table->string('path_adjunto_anular_disk')->nullable()->comment('disk archivo adjunto');
            $table->boolean('pendiente_anulacion')->default(false)->comment('estado para controlar la si esta pendiente de anulacion');
            $table->unsignedInteger('user_anulacion_id')->nullable()->comment('Id del usuario que solicita la anulacion');
            $table->timestamp('fecha_anulacion')->nullable()->comment('Fecha de anulacion');
            $table->timestamp('fecha_anulacion_confirm')->nullable()->comment('Fecha de anulacion confirmada');
            $table->timestamp('fecha_anulacion_denegada')->nullable();
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
            $table->integer('envio')->nullable();
            $table->integer('estado_condicion_envio')->nullable();
            $table->integer('estado_condicion_pedido')->nullable();
            $table->integer('estado_sobre')->nullable()->default(0);
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

        Schema::create('porcentajes', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('cliente_id')->index();
            $table->string('nombre')->nullable();
            $table->decimal('porcentaje', 2, 1)->nullable();
            $table->timestamps();
        });

        Schema::create('provincias', function (Blueprint $table) {

            $table->id();
            $table->string('provincia')->nullable();
            $table->string('departamento')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
        });

        Schema::create('ruc_backups', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('num_ruc');
            $table->unsignedBigInteger('user_id');
            $table->integer('cliente_id');
            $table->string('empresa')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
            $table->decimal('porcentaje', 2, 1)->nullable();
        });

        Schema::create('rucs', function (Blueprint $table) {

            $table->id();
            $table->string('num_ruc')->unique();
            $table->unsignedBigInteger('user_id')->index();
            $table->integer('cliente_id');
            $table->string('empresa')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
            $table->decimal('porcentaje', 2, 1)->nullable();
        });

        Schema::create('rucs_backup_updated', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('num_ruc');
            $table->unsignedBigInteger('user_id');
            $table->integer('cliente_id');
            $table->string('empresa')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
            $table->decimal('porcentaje', 2, 1)->nullable();
        });

        Schema::create('rucs_respaldo', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('num_ruc');
            $table->unsignedBigInteger('user_id');
            $table->integer('cliente_id');
            $table->string('empresa')->nullable();
            $table->integer('estado')->nullable();
            $table->timestamps();
            $table->decimal('porcentaje', 2, 1)->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('situacioncliente', function (Blueprint $table) {

            $table->bigIncrements('int');
            $table->string('anio', 4)->nullable();
            $table->string('mes', 100)->nullable();
            $table->unsignedBigInteger('cliente')->nullable();
            $table->string('situacion', 100)->nullable();
        });

        Schema::create('t_pedidos_barrido', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->string('codigo_dp')->nullable();
        });

        Schema::create('tabla_arreglo_envios_lima', function (Blueprint $table) {

            $table->string('codigos', 100)->nullable();
        });

        Schema::create('tabla_arreglo_envios_provincia', function (Blueprint $table) {

            $table->string('codigos', 100)->nullable();
        });

        Schema::create('temp_pedido_calc', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('correlativo', 7)->nullable();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('user_id');
            $table->string('creador')->nullable();
            $table->integer('pago')->nullable();
            $table->integer('pagado')->nullable();
            $table->string('destino')->nullable();
            $table->string('trecking')->nullable();
            $table->string('direccion')->nullable();
            $table->string('condicion_envio')->nullable();
            $table->integer('condicion_envio_code')->nullable();
            $table->string('condicion')->nullable();
            $table->integer('condicion_code')->nullable();
            $table->integer('condicion_int')->nullable();
            $table->string('codigo')->nullable();
            $table->string('notificacion')->nullable();
            $table->string('motivo', 1000)->nullable();
            $table->string('responsable')->nullable();
            $table->string('modificador')->nullable();
            $table->integer('devuelto')->nullable();
            $table->integer('cant_devuelto')->nullable();
            $table->string('observacion_devuelto')->nullable();
            $table->integer('estado');
            $table->string('path_adjunto_anular')->nullable()->comment('archivo adjunto antes de analizar');
            $table->string('path_adjunto_anular_disk')->nullable()->comment('disk archivo adjunto');
            $table->boolean('pendiente_anulacion')->default(false)->comment('estado para controlar la si esta pendiente de anulacion');
            $table->unsignedInteger('user_anulacion_id')->nullable()->comment('Id del usuario que solicita la anulacion');
            $table->timestamp('fecha_anulacion')->nullable()->comment('Fecha de anulacion');
            $table->timestamp('fecha_anulacion_confirm')->nullable()->comment('Fecha de anulacion confirmada');
            $table->timestamps();
            $table->timestamp('returned_at')->nullable();
            $table->integer('envio')->nullable();
            $table->integer('estado_condicion_envio')->nullable();
            $table->integer('estado_condicion_pedido')->nullable();
            $table->integer('estado_sobre')->nullable()->default(0);
        });

        Schema::create('temp_pedido_calc_2', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->string('tipo_banca')->nullable();
            $table->integer('envio')->nullable();
        });

        Schema::create('temp_pedido_calc_3', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->string('codigo')->nullable();
            $table->string('tipo_banca')->nullable();
            $table->integer('envio')->nullable();
        });

        Schema::create('temporal_situacion', function (Blueprint $table) {

            $table->string('situacion')->nullable();
            $table->bigInteger('cantidad');
        });

        Schema::create('tipo_movimientos', function (Blueprint $table) {

            $table->id();
            $table->string('descripcion', 100)->nullable();
            $table->string('banco', 100)->nullable();
        });

        Schema::create('titulares', function (Blueprint $table) {

            $table->id();
            $table->string('nombre')->nullable();
            $table->string('observacion')->nullable();
            $table->integer('estado');
            $table->timestamps();
        });

        Schema::create('update_individual_clientes', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->default(0);
            $table->integer('celular');
            $table->string('situacion')->nullable();
        });

        Schema::create('users', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->string('letra', 1)->nullable();
            $table->integer('estado');
            $table->string('rol');
            $table->string('supervisor')->nullable();
            $table->string('operario')->nullable();
            $table->string('llamada')->nullable();
            $table->string('jefe')->nullable();
            $table->string('identificador')->nullable();
            $table->string('exidentificador', 100)->nullable();
            $table->string('unificado', 100)->nullable();
            $table->string('meta_pedido')->nullable();
            $table->string('meta_cobro')->nullable();
            $table->integer('celular')->nullable();
            $table->string('provincia')->nullable();
            $table->string('distrito')->nullable();
            $table->string('direccion')->nullable();
            $table->string('referencia')->nullable();
            $table->unsignedBigInteger('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->boolean('excluir_meta')->default(false);
            $table->string('zona')->nullable();
            $table->timestamps();
        });

        Schema::create('validacion_rucs', function (Blueprint $table) {

            $table->string('ruc');
            $table->bigInteger('count_ruc')->nullable();
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('detalle_pagos', function (Blueprint $table) {
            $table->foreign(['pago_id'])->references(['id'])->on('pagos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('detalle_pedidos', function (Blueprint $table) {
            $table->foreign(['pedido_id'])->references(['id'])->on('pedidos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('direccion_pedidos', function (Blueprint $table) {
            $table->foreign(['pedido_id'])->references(['id'])->on('pedidos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('gasto_envios', function (Blueprint $table) {
            $table->foreign(['cliente_id'])->references(['id'])->on('clientes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('grupo_pedido_items', function (Blueprint $table) {
            $table->foreign(['grupo_pedido_id'])->references(['id'])->on('grupo_pedidos')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign(['pedido_id'])->references(['id'])->on('pedidos')->onUpdate('CASCADE')->onDelete('NO ACTION');
        });

        Schema::table('imagen_atencions', function (Blueprint $table) {
            $table->foreign(['pedido_id'])->references(['id'])->on('pedidos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('imagen_pedidos', function (Blueprint $table) {
            $table->foreign(['pedido_id'])->references(['id'])->on('pedidos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('pago_pedidos', function (Blueprint $table) {
            $table->foreign(['pago_id'])->references(['id'])->on('pagos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['pedido_id'])->references(['id'])->on('pedidos')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->foreign(['cliente_id'])->references(['id'])->on('clientes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreign(['cliente_id'])->references(['id'])->on('clientes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('porcentajes', function (Blueprint $table) {
            $table->foreign(['cliente_id'])->references(['id'])->on('clientes')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('rucs', function (Blueprint $table) {
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('rucs', function (Blueprint $table) {
            $table->dropForeign('rucs_user_id_foreign');
        });

        Schema::table('porcentajes', function (Blueprint $table) {
            $table->dropForeign('porcentajes_cliente_id_foreign');
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropForeign('pedidos_cliente_id_foreign');
            $table->dropForeign('pedidos_user_id_foreign');
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign('pagos_cliente_id_foreign');
            $table->dropForeign('pagos_user_id_foreign');
        });

        Schema::table('pago_pedidos', function (Blueprint $table) {
            $table->dropForeign('pago_pedidos_pago_id_foreign');
            $table->dropForeign('pago_pedidos_pedido_id_foreign');
        });


        Schema::table('imagen_pedidos', function (Blueprint $table) {
            $table->dropForeign('imagen_pedidos_pedido_id_foreign');
        });

        Schema::table('imagen_atencions', function (Blueprint $table) {
            $table->dropForeign('imagen_atencions_pedido_id_foreign');
        });

        Schema::table('grupo_pedido_items', function (Blueprint $table) {
            $table->dropForeign('grupo_pedido_items_grupo_pedido_id_foreign');
            $table->dropForeign('grupo_pedido_items_pedido_id_foreign');
        });

        Schema::table('gasto_envios', function (Blueprint $table) {
            $table->dropForeign('gasto_envios_cliente_id_foreign');
            $table->dropForeign('gasto_envios_user_id_foreign');
        });

        Schema::table('direccion_pedidos', function (Blueprint $table) {
            $table->dropForeign('direccion_pedidos_pedido_id_foreign');
        });

        Schema::table('detalle_pedidos', function (Blueprint $table) {
            $table->dropForeign('detalle_pedidos_pedido_id_foreign');
        });

        Schema::table('detalle_pagos', function (Blueprint $table) {
            $table->dropForeign('detalle_pagos_pago_id_foreign');
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropForeign('clientes_user_id_foreign');
        });

        Schema::dropIfExists('validacion_rucs');

        Schema::dropIfExists('users');

        Schema::dropIfExists('update_individual_clientes');

        Schema::dropIfExists('titulares');

        Schema::dropIfExists('tipo_movimientos');

        Schema::dropIfExists('temporal_situacion');

        Schema::dropIfExists('temp_pedido_calc_3');

        Schema::dropIfExists('temp_pedido_calc_2');

        Schema::dropIfExists('temp_pedido_calc');

        Schema::dropIfExists('tabla_arreglo_envios_provincia');

        Schema::dropIfExists('tabla_arreglo_envios_lima');

        Schema::dropIfExists('t_pedidos_barrido');

        Schema::dropIfExists('situacioncliente');

        Schema::dropIfExists('sessions');

        Schema::dropIfExists('rucs_respaldo');

        Schema::dropIfExists('rucs_backup_updated');

        Schema::dropIfExists('rucs');

        Schema::dropIfExists('ruc_backups');

        Schema::dropIfExists('provincias');

        Schema::dropIfExists('porcentajes');

        Schema::dropIfExists('pedidosporrevisar');

        Schema::dropIfExists('pedidos_temp');

        Schema::dropIfExists('pedidos_respaldo');

        Schema::dropIfExists('pedidos_condicion_poratender');

        Schema::dropIfExists('pedidos');

        Schema::dropIfExists('pedido_movimiento_estados');

        Schema::dropIfExists('pedido_clientes');

        Schema::dropIfExists('pedido_app');

        Schema::dropIfExists('password_resets');

        Schema::dropIfExists('pagos');

        Schema::dropIfExists('pago_pedidos');

        Schema::dropIfExists('nuevo_estado_pedidos');

        Schema::dropIfExists('movimiento_bancarios');

        Schema::dropIfExists('mitemp');

        Schema::dropIfExists('migra_dg_pedido_provincia');

        Schema::dropIfExists('migra_dg_pedido');

        Schema::dropIfExists('miemporal');

        Schema::dropIfExists('listado_revisar_clientes');

        Schema::dropIfExists('listado_resultados');

        Schema::dropIfExists('listado_pedidos_clientes_cc');

        Schema::dropIfExists('listado_pedidos_clientes');

        Schema::dropIfExists('listado_clientes');

        Schema::dropIfExists('listaa');

        Schema::dropIfExists('lista_pedidos_migrar');

        Schema::dropIfExists('list_pedidos_d');

        Schema::dropIfExists('list_pedidos_c');

        Schema::dropIfExists('list_pedidos_b');

        Schema::dropIfExists('list_pedidos_a');

        Schema::dropIfExists('list_casi_abandono');

        Schema::dropIfExists('imagen_pedidos');

        Schema::dropIfExists('imagen_atencions');

        Schema::dropIfExists('grupo_pedidos');

        Schema::dropIfExists('grupo_pedido_items');

        Schema::dropIfExists('grupo_pagos_org');

        Schema::dropIfExists('grupo_detalle_pagos');

        Schema::dropIfExists('gasto_pedidos');

        Schema::dropIfExists('gasto_envios');

        Schema::dropIfExists('fix_tabla');

        Schema::dropIfExists('failed_jobs');

        Schema::dropIfExists('export_ex');

        Schema::dropIfExists('estado_pedidos');

        Schema::dropIfExists('envios_fix');

        Schema::dropIfExists('entidad_bancarias');

        Schema::dropIfExists('empresas_pedidos_old');

        Schema::dropIfExists('eee');

        Schema::dropIfExists('distritos');

        Schema::dropIfExists('direcciongrupoupdate');

        Schema::dropIfExists('direccion_pedidos');

        Schema::dropIfExists('direccion_grupos');

        Schema::dropIfExists('direccion_grupo_calcular_index');

        Schema::dropIfExists('direccion_grupo_calcular');

        Schema::dropIfExists('direccion_envios');

        Schema::dropIfExists('direccion_envioc');

        Schema::dropIfExists('direc_table');

        Schema::dropIfExists('devolucions');

        Schema::dropIfExists('deuda_nodeuda');

        Schema::dropIfExists('detallepedidoidvalidar');

        Schema::dropIfExists('detalle_pedidos_atendido');

        Schema::dropIfExists('detalle_pedidos');

        Schema::dropIfExists('detalle_pagos');

        Schema::dropIfExists('departamentos');

        Schema::dropIfExists('cuenta_dg');

        Schema::dropIfExists('cuenta_bancarias');

        Schema::dropIfExists('cron_devueltos_b');

        Schema::dropIfExists('cron_devueltos');

        Schema::dropIfExists('count_pedidos_cliente');

        Schema::dropIfExists('corregir_rucs');

        Schema::dropIfExists('corregir_ahora');

        Schema::dropIfExists('conta_pedidos_a');

        Schema::dropIfExists('conta_pedidos');

        Schema::dropIfExists('completar_atendidopor_pedidos_userid_operario');

        Schema::dropIfExists('completar_atendidopor_pedidos_userid');

        Schema::dropIfExists('completar_atendidopor_pedidos');

        Schema::dropIfExists('codigos_fernandez');

        Schema::dropIfExists('codigos_calculo');

        Schema::dropIfExists('codigo_val_no');

        Schema::dropIfExists('codigo_val');

        Schema::dropIfExists('clientes_respaldo');

        Schema::dropIfExists('clientes_compilar');

        Schema::dropIfExists('clientes');

        Schema::dropIfExists('cliente_listado_corregir');

        Schema::dropIfExists('aux_dgp');

        Schema::dropIfExists('aux_dg');

        Schema::dropIfExists('aux_clientes');

        Schema::dropIfExists('asd');

        Schema::dropIfExists('aa_temp');

        Schema::dropIfExists('a123');
    }
};
