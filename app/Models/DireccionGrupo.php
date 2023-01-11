<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionGrupo extends Model
{
    use HasFactory;
    use CommonModel;

    //condicion_envio
    const CE_EN_REPARTO = "EN REPARTO";//1
    const CE_EN_REPARTO_CODE = 8;//1


    const CE_ENTREGADO = "ENTREGADO";//2
    const CE_ENTREGADO_CODE = 10;//2

    const CE_ENTREGADO_SIN_SOBRE = "ENTREGADO SIN SOBRE";
    const CE_ENTREGADO_SIN_SOBRE_CODE = 14;

    const CE_BANCARIZACION = "BANCARIZACION";//2
    const CE_BANCARIZACION_CODE = 4;//2


    //subcondicion_envio
    const SCE_REGISTRADO = 'REGISTRADO';
    const SCE_EN_CAMINO = 'EN CAMINO';
    const SCE_EN_TIENDA_AGENTE = 'EN TIENDA/AGENTE';
    const SCE_ENTREGADO = 'ENTREGADO';
    const SCE_NO_ENTREGADO = 'NO ENTREGADO';
    const NULL = 'NULL';

    protected $guarded = ['id'];
    protected $casts = [
        'fecha_recepcion' => 'date'
    ];

    protected static function booted()
    {
        parent::booted();
        self::created(function (self $model) {
            $model->update([
                'correlativo' => 'ENV' . $model->id
            ]);
        });
    }

    public function scopeObservado($query)
    {
        return $query->where($this->qualifyColumn('motorizado_status'), Pedido::ESTADO_MOTORIZADO_OBSERVADO);
    }

    public function scopeNoContesto($query)
    {
        return $query->where($this->qualifyColumn('motorizado_status'), Pedido::ESTADO_MOTORIZADO_NO_CONTESTO);
    }

    public function scopeContestoNoObservado($query)
    {
        return $query->whereNotIn($this->qualifyColumn('motorizado_status'), [Pedido::ESTADO_MOTORIZADO_OBSERVADO, Pedido::ESTADO_MOTORIZADO_NO_CONTESTO]);
    }


    public function motorizadoHistories()
    {
        return $this->hasMany(PedidoMotorizadoHistory::class, 'direccion_grupo_id')->orderByDesc('created_at');
    }

    public function gastoEnvio()
    {
        return $this->hasOne(GastoEnvio::class, 'direcciongrupo');
    }

    public function gastoEnvios()
    {
        return $this->hasMany(GastoEnvio::class, 'direcciongrupo');
    }

    public function direccionEnvio()
    {
        return $this->hasOne(DireccionEnvio::class, 'direcciongrupo');
    }

    public function direccionEnvios()
    {
        return $this->hasMany(DireccionEnvio::class, 'direcciongrupo');
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'direccion_grupo');
    }

    public static function desvincularPedido(self $grupo, Pedido $pedido,$sustento=null)
    {
        if ($grupo->pedidos()->count() > 1) {
            $newgrupo = $grupo->replicate()->fill([
                'motorizado_status' => Pedido::ESTADO_MOTORIZADO_OBSERVADO,
                'motorizado_sustento_text' => $sustento??$pedido->cambio_direccion_sustento,
            ]);
            $newgrupo->save();

            $newgrupo->update([
                'correlativo' => 'ENV' . $newgrupo->id,
            ]);

            $pedido->update([
                'direccion_grupo' => $newgrupo->id
            ]);
            $detalle = $pedido->detallePedido;

            $grupo->update([
                'codigos' => collect(explode(',', $grupo->codigos))->map(fn($c) => trim($c))->filter()->filter(fn($c) => $c != $pedido->codigo)->join(','),
                'producto' => collect(explode(',', $grupo->producto))->map(fn($c) => trim($c))->filter()->filter(fn($c) => $c != $detalle->nombre_empresa)->join(','),
            ]);
            return $newgrupo;
        } else {
            $grupo->update([
                'motorizado_status' => Pedido::ESTADO_MOTORIZADO_OBSERVADO,
                'motorizado_sustento_text' => $sustento??$pedido->cambio_direccion_sustento,
            ]);
            return $grupo;
        }
    }
}
