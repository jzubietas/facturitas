<?php

namespace App\Jobs;

use App\Models\DetallePedido;
use App\Models\HistoriaPedidos;
use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostUpdatePedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $codigo;
    public function __construct($codigo)
    {
        //
      $this->codigo = $codigo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
      //\Log::info("PostUpdatePedido -> " . $this->codigo);

      $pedido=Pedido::where('codigo',$this->codigo)->first();
      $dpedido=DetallePedido::where('codigo',$this->codigo)->first();

      //crear historial
      $estado_correccion = $pedido->estado_correccion;

      HistoriaPedidos::create([
        'pedido_id' => $pedido->id,
        'correlativo' => $pedido->correlativo,
        'cliente_id' => $pedido->cliente_id,
        'user_id' => $pedido->user_id,
        'identificador' => $pedido->identificador,
        'exidentificador' => $pedido->exidentificador,
        'icelular_asesor' => $pedido->icelular_asesor,
        'celular_cliente' => $pedido->celular_cliente,
        'icelular_cliente' => $pedido->icelular_cliente,
        'creador' => $pedido->creador,
        'pago' => $pedido->pago,
        'pagado' => $pedido->pagado,
        'condicion_envio' => $pedido->condicion_envio,
        'condicion_envio_code' => $pedido->condicion_envio_code,
        'condicion_envio_at' => $pedido->condicion_envio_at,
        'codigo' => $pedido->codigo,
        'motivo' => $pedido->motivo,
        'responsable' => $pedido->responsable,
        'modificador' => $pedido->modificador,
        'estado' => $pedido->estado,
        'da_confirmar_descarga' => $pedido->da_confirmar_descarga,
        'estado_sobre' => $pedido->estado_sobre,
        'estado_consinsobre' => $pedido->estado_consinsobre,
        'env_destino' => $pedido->env_destino,
        'env_distrito' => $pedido->env_distrito,
        'env_zona' => $pedido->env_zona,
        'env_zona_asignada' => $pedido->env_zona_asignada,
        'env_nombre_cliente_recibe' => $pedido->env_nombre_cliente_recibe,
        'env_celular_cliente_recibe' => $pedido->env_celular_cliente_recibe,
        'env_cantidad' => $pedido->env_cantidad,
        'env_direccion' => $pedido->env_direccion,
        'env_tracking' => $pedido->env_tracking,
        'env_referencia' => $pedido->env_referencia,
        'env_numregistro' => $pedido->env_numregistro,
        'env_rotulo' => $pedido->env_numregistro,
        'env_observacion' => $pedido->env_numregistro,
        'env_gmlink' => $pedido->env_gmlink,
        'env_importe' => $pedido->env_importe,
        'estado_ruta' => $pedido->estado_ruta,
        'direccion_grupo' => $pedido->direccion_grupo,
        'estado_correccion' => $estado_correccion,
        //columnas de
        'nombre_empresa' => $dpedido->nombre_empresa,
        'mes' => $dpedido->mes,
        'anio' => $dpedido->anio,
        'ruc' => $dpedido->ruc,
        'cantidad' => $dpedido->cantidad,
        'tipo_banca' => $dpedido->tipo_banca,
        'porcentaje' => $dpedido->porcentaje,
        'ft' => $dpedido->ft,
        'courier' => $dpedido->courier,
        'total' => $dpedido->total,
        'saldo' => $dpedido->saldo,
        'descripcion' => $dpedido->descripcion,
        'nota' => $dpedido->nota,
        //campos de detalle
      ]);


    }
}
