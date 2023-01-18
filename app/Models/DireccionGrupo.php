<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
    protected $dates = [
        'fecha_recepcion',
        'fecha_salida',
        'fecha',
        'cambio_direccion_at',
        'fecha_recepcion_motorizado',
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

    public function motorizado()
    {
        return $this->belongsTo(User::class, 'motorizado_id');
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

    public static function restructurarCodigos(self $grupo)
    {
        if ($grupo->distribucion == 'OLVA') {
            $relacion = $grupo->pedidos()->activo()
                ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', 'pedidos.id')
                ->select([
                    'pedidos.codigo',
                    'detalle_pedidos.nombre_empresa',
                    \DB::raw("(case when pedidos.destino='PROVINCIA' then pedidos.env_tracking
                                when pedidos.destino='LIMA' then pedidos.env_direccion
                                  end) as direccion"),
                    \DB::raw("(case when pedidos.destino='PROVINCIA' then pedidos.env_numregistro
                                when pedidos.destino='LIMA' then pedidos.env_referencia
                                  end) as referencia"),
                    \DB::raw("(case when pedidos.destino='PROVINCIA' then pedidos.env_rotulo
                                when pedidos.destino='LIMA' then pedidos.env_observacion
                                  end) as observacion"),
                ])
                ->get();
        } else {
            $relacion =$grupo->pedidos()->activo()
                ->join('detalle_pedidos', 'detalle_pedidos.pedido_id', 'pedidos.id')
                ->select([
                    'pedidos.codigo',
                    'detalle_pedidos.nombre_empresa',
                    'pedidos.env_direccion as direccion',
                    'pedidos.env_referencia as referencia',
                    'pedidos.env_observacion as observacion'
                ])
                ->get();
        }
        if ($relacion->count() > 0) {
            $grupo->update([
                'codigos' => $relacion->pluck('codigo')->join(', '),
                'producto' => $relacion->pluck('nombre_empresa')->join(', '),
                'direccion' => $relacion->pluck('direccion')->trim()->unique()->join(', '),
                'referencia' => $relacion->pluck('referencia')->trim()->unique()->join(', '),
                'observacion' => $relacion->pluck('observacion')->trim()->unique()->join(', '),
                'cantidad' => $relacion->count(),
            ]);
        } else {
            $grupo->update([
                'estado' => 0,
                'motorizado_status' => 0
            ]);
        }

    }

    public static function cambiarDireccion(self $grupo, Pedido $pedido, array $data)
    {
        if (\Str::upper($data['zona']) != 'OLVA') {
            $dataa = [
                'env_nombre_cliente_recibe' => trim($data['nombre_cliente_recibe']),
                'env_celular_cliente_recibe' => trim($data['celular_cliente_recibe']),
                'env_direccion' => trim($data['direccion']),
                'env_referencia' => trim($data['referencia']),
                'env_distrito' => trim($data['distrito']),
                'env_observacion' => trim($data['observacion']),
            ];
            $datab = [
                'env_nombre_cliente_recibe' => trim($pedido->env_nombre_cliente_recibe),
                'env_celular_cliente_recibe' => trim($pedido->env_celular_cliente_recibe),
                'env_direccion' => trim($pedido->env_direccion),
                'env_referencia' => trim($pedido->env_referencia),
                'env_distrito' => trim($pedido->env_distrito),
                'env_observacion' => trim($pedido->env_observacion),
            ];
            if ($dataa == $datab) {
                return true;
            }
        }
        switch ($grupo->condicion_envio_code) {
            case Pedido::REPARTO_COURIER_INT:
            case Pedido::ENVIO_COURIER_JEFE_OPE_INT:
            case Pedido::ENVIO_MOTORIZADO_COURIER_INT:
                if (\Str::upper($data['zona']) != 'OLVA') {
                    $cantidad = $grupo->pedidos()->count();
                    if ($cantidad > 1) {
                        $pedido->update([
                            'direccion_grupo' => null
                        ]);
                        DireccionGrupo::restructurarCodigos($grupo);
                        $pedido->update([
                            'env_nombre_cliente_recibe' => $data['nombre_cliente_recibe'],
                            'env_celular_cliente_recibe' => $data['celular_cliente_recibe'],
                            'env_direccion' => $data['direccion'],
                            'env_referencia' => $data['referencia'],
                            'env_distrito' => $data['distrito'],
                            'env_observacion' => $data['observacion'],
                            'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                            'cambio_direccion_at' => now(),
                        ]);
                        GrupoPedido::desvincularPedido($pedido, false, true);
                    } else {
                        if ($pedido->env_distrito != $data['distrito']) {
                            $pedido->update([
                                'direccion_grupo' => null,
                            ]);
                            DireccionGrupo::restructurarCodigos($grupo);
                            $grupo->update([
                                'estado' => 0,
                                'motorizado_status' => 0,
                                'motorizado_sustento_text' => 'Los pedidos fueron enviados a con direccion, por motivo de cambio de dirección',
                            ]);
                            $pedido->update([
                                'env_nombre_cliente_recibe' => $data['nombre_cliente_recibe'],
                                'env_celular_cliente_recibe' => $data['celular_cliente_recibe'],
                                'env_direccion' => $data['direccion'],
                                'env_referencia' => $data['referencia'],
                                'env_distrito' => $data['distrito'],
                                'env_observacion' => $data['observacion'],
                                'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                                'cambio_direccion_at' => null,
                            ]);
                            GrupoPedido::createGroupByPedido($pedido, false, true);
                        } else {
                            $grupo->update([
                                'nombre_cliente' => $data['nombre_cliente_recibe'],
                                'celular_cliente' => $data['celular_cliente_recibe'],
                                'direccion' => $data['direccion'],
                                'referencia' => $data['referencia'],
                                'distrito' => $data['distrito'],
                                'observacion' => $data['observacion'],
                                'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                                'cambio_direccion_at' => now(),
                            ]);
                            $pedido->update([
                                'env_nombre_cliente_recibe' => $data['nombre_cliente_recibe'],
                                'env_celular_cliente_recibe' => $data['celular_cliente_recibe'],
                                'env_direccion' => $data['direccion'],
                                'env_referencia' => $data['referencia'],
                                'env_distrito' => $data['distrito'],
                                'env_observacion' => $data['observacion'],
                                'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                                'cambio_direccion_at' => now(),
                            ]);
                        }
                    }
                } else {
                    $pedido->update([
                        'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                        'env_numregistro' => $data['env_numregistro'],
                        'env_tracking' => $data['env_tracking'],
                        'env_importe' => $data['env_importe'],
                        'env_rotulo' => $data['env_rotulo'],
                        'cambio_direccion_at' => now(),
                    ]);
                }
                break;
            case Pedido::RECEPCION_MOTORIZADO_INT:
            case Pedido::MOTORIZADO_INT:
                if (\Str::upper($data['zona']) != 'OLVA') {
                    $cantidad = $grupo->pedidos()->count();
                    if ($cantidad > 1) {
                        $grupo = DireccionGrupo::desvincularPedido($grupo, $pedido, $data['cambio_direccion_sustento']);
                        $grupo->update([
                            'nombre_cliente' => $data['nombre_cliente_recibe'],
                            'celular_cliente' => $data['celular_cliente_recibe'],
                            'direccion' => $data['direccion'],
                            'referencia' => $data['referencia'],
                            'distrito' => $data['distrito'],
                            'observacion' => $data['observacion'],
                            'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                            'cambio_direccion_at' => null,
                        ]);
                    } else {
                        if ($pedido->env_distrito != $data['distrito']) {
                            $grupo->update([
                                'nombre_cliente' => $data['nombre_cliente_recibe'],
                                'celular_cliente' => $data['celular_cliente_recibe'],
                                'direccion' => $data['direccion'],
                                'referencia' => $data['referencia'],
                                'distrito' => $data['distrito'],
                                'observacion' => $data['observacion'],
                                'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                                'motorizado_status' => Pedido::ESTADO_MOTORIZADO_OBSERVADO,
                                'motorizado_sustento_text' => 'Cambio de dirección: ' . $data['cambio_direccion_sustento'],
                                'cambio_direccion_at' => null,
                            ]);
                        } else {
                            $grupo->update([
                                'nombre_cliente' => $data['nombre_cliente_recibe'],
                                'celular_cliente' => $data['celular_cliente_recibe'],
                                'direccion' => $data['direccion'],
                                'referencia' => $data['referencia'],
                                'distrito' => $data['distrito'],
                                'observacion' => $data['observacion'],
                                //'motorizado_status' => 0,
                                'motorizado_sustento_text' => 'Cambio de dirección: ' . $data['cambio_direccion_sustento'],
                                'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                                'cambio_direccion_at' => now(),
                            ]);
                        }
                    }
                    $pedido->update([
                        'env_nombre_cliente_recibe' => $data['nombre_cliente_recibe'],
                        'env_celular_cliente_recibe' => $data['celular_cliente_recibe'],
                        'env_direccion' => $data['direccion'],
                        'env_referencia' => $data['referencia'],
                        'env_distrito' => $data['distrito'],
                        'env_observacion' => $data['observacion'],
                        'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                    ]);
                } else {
                    $pedido->update([
                        'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
                        'env_numregistro' => $data['env_numregistro'],
                        'env_tracking' => $data['env_tracking'],
                        'env_importe' => $data['env_importe'],
                        'env_rotulo' => $data['env_rotulo'],
                        'cambio_direccion_at' => now(),
                    ]);
                }
                break;
            default:
                return false;
        }
        return true;
    }

    public static function createByPedido(Pedido $pedido)
    {
        $groupData = [
            'condicion_envio_code' => $pedido->condicion_envio_code,
            'condicion_envio_at' =>  $pedido->condicion_envio_at,
            'condicion_envio' => $pedido->condicion_envio,
            'distribucion' => $pedido->env_zona,
            'destino' => $pedido->env_destino,
            'direccion' => $pedido->env_direccion,
            'estado' => '1',

            'cliente_id' => $pedido->cliente_id,
            'user_id' => $pedido->user_id,

            'nombre' => $pedido->env_nombre_cliente_recibe,
            'celular' => $pedido->env_celular_cliente_recibe,

            'nombre_cliente' => $pedido->cliente->nombre,
            'celular_cliente' => $pedido->cliente->celular,
            'icelular_cliente' => $pedido->cliente->icelular,

            'distrito' => $pedido->env_distrito,
            'referencia' => $pedido->env_referencia,//nro registro
            'observacion' => $pedido->env_observacion,//rotulo
            'motorizado_id' => 0,
            'identificador' => $pedido->cliente->user->identificador,
        ];
        if ($pedido->env_zona == 'OLVA') {
            $groupData['direccion'] = $pedido->env_tracking;
            $groupData['referencia'] = $pedido->env_referencia;
            $groupData['observacion'] = $pedido->env_observacion;
        }
        $grupo = DireccionGrupo::create($groupData);
        $pedido->update([
            'direccion_grupo' => $grupo->id
        ]);
        DireccionGrupo::restructurarCodigos($grupo);
        return $grupo;
    }

    public static function desvincularPedido(self $grupo, Pedido $pedido, $sustento = null, $motorizado_status = Pedido::ESTADO_MOTORIZADO_OBSERVADO)
    {
        return self::desvincularPedidos($grupo, collect([$pedido]), $sustento, $motorizado_status);
    }

    public static function desvincularPedidos(self $grupo, Collection $pedidos, $sustento = null, $motorizado_status = Pedido::ESTADO_MOTORIZADO_OBSERVADO)
    {
        if ($grupo->pedidos()->count() > 1) {
            $newgrupo = $grupo->replicate()->fill([
                'motorizado_status' => $motorizado_status,
                'motorizado_sustento_text' => $sustento,
            ]);

            $newgrupo->save();

            $newgrupo->update([
                'correlativo' => 'ENV' . $newgrupo->id,
            ]);

            foreach ($pedidos as $pedido) {
                $pedido->update([
                    'direccion_grupo' => $newgrupo->id
                ]);
            }
            self::restructurarCodigos($newgrupo);
            self::restructurarCodigos($grupo);
            return $newgrupo;
        } else {
            $grupo->update([
                'motorizado_status' => $motorizado_status,
                'motorizado_sustento_text' => $sustento,
            ]);
            return $grupo;
        }
    }

    public static function addNoRecibidoAuthorization(self $grupo)
    {
        setting()->load();
        $motorizado_id = $grupo->motorizado_id;
        $key = "motorizado.authorization.ruta.paquetes.$motorizado_id";
        $result = setting($key, []);
        $result[] = $grupo->id;
        setting([
            $key => collect($result)->unique()->values()->all()
        ])->save();
    }

    /**
     * @param $motorizadoId
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */

    public static function getNoRecibidoAuthorization($motorizadoId)
    {
        setting()->load();
        $key = "motorizado.authorization.ruta.paquetes.$motorizadoId";
        return setting($key, []);
    }

    public static function clearNoRecibidoAuthorization($motorizadoId)
    {
        setting()->load();
        $key = "motorizado.authorization.ruta.paquetes.$motorizadoId";
        setting()->forget($key);
        setting()->save();
    }
}
