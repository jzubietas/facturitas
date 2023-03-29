<?php

namespace App\Models;

use App\Traits\CommonModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DireccionGrupo extends Model implements HasMedia
{
    use HasFactory;
    use CommonModel;
    use InteractsWithMedia;

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
    const SCE_RECEPCIONADO = 'RECEPCIONADO - OLVA';
    const SCE_EN_CAMINO = 'EN CAMINO - OLVA';
    const SCE_EN_TIENDA_AGENTE = 'EN TIENDA/AGENTE - OLVA';
    const SCE_ENTREGADO = 'ENTREGADO - OLVA';
    const SCE_NO_ENTREGADO = 'NO ENTREGADO - OLVA';

    const  OLVA_OTROS="01";
    const  OLVA_OTROS2="02";
    const  OLVA_CONFIRMACION="03";
    const  OLVA_EN_ALMACEN="04";
    const  OLVA_DESPACHADO="05";
    const  OLVA_REGISTRADO="06";
    const  OLVA_ASIGNADO="07";
    const  OLVA_MOTIVADO="08";
    const  OLVA_NO_ENTREGADO="09";
    const  OLVA_SINIESTRADO="10";
    const  OLVA_NOEXISTE="11";

   /* public static function getEstadosOlvaFinal(self $grupo){
        return DireccionGrupo::$getEstadosOlva($grupo->courier_estado);
    }*/

    public static $getEstadosOlva = [
        'OTROS' => DireccionGrupo::OLVA_OTROS,
        'OTROS2' => DireccionGrupo::OLVA_OTROS2,
        'CONFIRMACION EN TIENDA' => DireccionGrupo::OLVA_CONFIRMACION,
        'EN ALMACEN' => DireccionGrupo::OLVA_EN_ALMACEN,
        'DESPACHADO' => DireccionGrupo::OLVA_DESPACHADO,
        'REGISTRADO' => DireccionGrupo::OLVA_REGISTRADO,
        'ASIGNADO' => DireccionGrupo::OLVA_ASIGNADO,
        'MOTIVADO' => DireccionGrupo::OLVA_MOTIVADO,
        'NO ENTREGADO' => DireccionGrupo::OLVA_NO_ENTREGADO,
        'SINIESTRADO' => DireccionGrupo::OLVA_SINIESTRADO,
        '' => DireccionGrupo::OLVA_NOEXISTE,
    ];

    protected $guarded = ['id'];
    protected $dates = [
        'fecha_recepcion',
        'fecha_salida',
        'fecha',
        'cambio_direccion_at',
        'fecha_recepcion_motorizado',
        'reprogramacion_at',
        'reprogramacion_solicitud_at',
        'reprogramacion_accept_at',
        'courier_sync_at',
        'courier_failed_sync_at',
        'add_screenshot_at',
    ];

    protected $appends = [
        'is_reprogramado',
        'fecha_salida_format'
    ];

    protected $casts = [
        'courier_data' => 'object',
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

    public function scopeInOlvaPending($query)
    {
        return $query->whereIn($this->qualifyColumn('condicion_envio_code'), [
            Pedido::RECEPCIONADO_OLVA_INT,
            Pedido::EN_CAMINO_OLVA_INT,
            Pedido::EN_TIENDA_AGENTE_OLVA_INT,
        ]);
    }

    public function scopeInOlva($query)
    {
        return $query->whereIn($this->qualifyColumn('condicion_envio_code'), [
            Pedido::RECEPCIONADO_OLVA_INT,
            Pedido::EN_CAMINO_OLVA_INT,
            Pedido::EN_TIENDA_AGENTE_OLVA_INT,
            Pedido::NO_ENTREGADO_OLVA_INT,
            //Pedido::ENTREGADO_PROVINCIA_INT,
        ]);
    }

    public function scopeInOlvaAll($query)
    {
        return $query->whereIn($this->qualifyColumn('condicion_envio_code'), [
            Pedido::RECEPCIONADO_OLVA_INT,
            Pedido::EN_CAMINO_OLVA_INT,
            Pedido::EN_TIENDA_AGENTE_OLVA_INT,
            Pedido::ENTREGADO_PROVINCIA_INT,
            Pedido::NO_ENTREGADO_OLVA_INT,
        ]);
    }

    public function scopeInOlvaFinalizado($query)
    {
        return $query->whereIn($this->qualifyColumn('condicion_envio_code'), [
            Pedido::ENTREGADO_PROVINCIA_INT,
        ]);
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

    public function scopeReprogramados($query)
    {
        //$this->reprogramacion_at != null && $this->reprogramacion_accept_at == null;
        return $query->whereNotNull($this->qualifyColumn('reprogramacion_at'))
            ->whereNull($this->qualifyColumn('reprogramacion_accept_at'));
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

    public function getIsReprogramadoAttribute()
    {
        return $this->reprogramacion_at != null && $this->reprogramacion_accept_at == null;
    }

    protected $fillable = [
        'id',
        'correlativo',
        'destino',
        'distribucion',
        'condicion_envio_code',
        'condicion_envio_at',
        'condicion_envio',
        'subcondicion_envio',
        'condicion_sobre',
        'foto1',
        'foto2',
        'foto3',
        'fecha_recepcion',
        'atendido_por',
        'atendido_por_id',
        'nombre_cliente',
        'celular_cliente',
        'icelular_cliente',
        'estado',
        'motorizado_id',
        'created_at',
        'updated_at',
        'cliente_id',
        'user_id',
        'codigos',
        'producto',
        'identificador',
        'celular',
        'nombre',
        'fecha',
        'cantidad',
        'importe',
        'direccion',
        'referencia',
        'observacion',
        'gmlink',
        'distrito',
        'destino2',
        'pedido_id',
        'fecha_salida',
        'motorizado_status',
        'motorizado_sustento_text',
        'motorizado_sustento_foto',
        'codigos_confirmados',
        'cambio_direccion_sustento',
        'fecha_recepcion_motorizado',
        'cambio_direccion_at',
        'estado_consinsobre',
        'reprogramacion_at',
        'reprogramacion_solicitud_user_id',
        'reprogramacion_solicitud_at',
        'reprogramacion_accept_user_id',
        'reprogramacion_accept_at',
        'fecha_salida_old_at',
        'relacionado',
        'courier_sync_at',
        'courier_failed_sync_at',
        'courier_sync_finalized',
        'courier_estado',
        'courier_data',
        'add_screenshot_at',
        'cod_recojo',
        'env_sustento_recojo'
    ];

    public function getFechaSalidaFormatAttribute()
    {
        return optional($this->fecha_salida)->format('d-m-Y');
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
            $relacion = $grupo->pedidos()->activo()
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
            $codigos = $relacion->pluck('codigo')->trim();
            $confirmados = collect(explode(',', $grupo->codigos_confirmados))->trim()
                ->filter(fn($c) => in_array($c, $codigos->all()));
            $grupo->update([
                'codigos' => $codigos->join(','),
                'producto' => $relacion->pluck('nombre_empresa')->trim()->map(fn($txt) => \Str::replace(',', ' - ', $txt))->join(','),
                'direccion' => $relacion->pluck('direccion')->trim()->map(fn($txt) => \Str::replace(',', ' - ', $txt))->unique()->join(','),
                'referencia' => $relacion->pluck('referencia')->trim()->map(fn($txt) => \Str::replace(',', ' - ', $txt))->unique()->join(','),
                'observacion' => $relacion->pluck('observacion')->trim()->unique()->join(','),
                'cantidad' => $relacion->count(),
                'codigos_confirmados' => $confirmados->unique()->join(','),
            ]);
        } else {
            $grupo->update([
                'estado' => 0,
                'codigos_confirmados' => '',
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
                    }
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
                } else {
                    $pedido->update([
                        'env_numregistro' => $data['env_numregistro'],
                        'env_tracking' => $data['env_tracking'],
                        'env_importe' => $data['env_importe'],
                        'env_rotulo' => $data['env_rotulo'],
                        'cambio_direccion_sustento' => $data['cambio_direccion_sustento'],
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
            'condicion_envio_at' => $pedido->condicion_envio_at,
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
            'gmlink' => $pedido->env_gmlink,
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

    public static function reagruparByPedido(DireccionGrupo $oldgrupo, Pedido $pedido, $condicion_envio_code)
    {

        $groupData = [
            'condicion_envio_code' => $condicion_envio_code,
            'condicion_envio' => Pedido::$estadosCondicionEnvioCode[$condicion_envio_code],
            'distribucion' => $oldgrupo->env_zona,
            'destino' => $oldgrupo->env_destino,
            'direccion' => $oldgrupo->env_direccion,
            'estado' => '1',

            'cliente_id' => $oldgrupo->cliente_id,
            'user_id' => $oldgrupo->user_id,

            'nombre' => $oldgrupo->nombre,
            'celular' => $oldgrupo->celular,

            'nombre_cliente' => $oldgrupo->nombre_cliente,
            'celular_cliente' => $oldgrupo->celular_cliente,
            'icelular_cliente' => $oldgrupo->icelular_cliente,

            'distrito' => $oldgrupo->distrito,
            'referencia' => $oldgrupo->referencia,//nro registro
            'observacion' => $oldgrupo->observacion,//rotulo
            'gmlink' => $oldgrupo->gmlink,
            'motorizado_id' => $oldgrupo->motorizado_id,
            'identificador' => $oldgrupo->identificador,
        ];

        $grupo = DireccionGrupo::where($groupData)->first();

        if ($grupo == null) {
            $grupo = $oldgrupo->replicate();
            $grupo->save();
        }

        $pedido->update([
            'direccion_grupo' => $grupo->id
        ]);

        $pedido->update([
            'direccion_grupo' => $grupo->id
        ]);

        DireccionGrupo::restructurarCodigos($oldgrupo);
        DireccionGrupo::restructurarCodigos($grupo);
        DireccionGrupo::cambiarCondicionEnvio($grupo, $condicion_envio_code);
        return $grupo;
    }

    public static function observarGrupo(self $grupo, $sustento = null, $motorizado_status = Pedido::ESTADO_MOTORIZADO_OBSERVADO)
    {
        $grupo->update([
            'motorizado_status' => $motorizado_status,
            'motorizado_sustento_text' => $sustento,
        ]);

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


    public static function moverAMotorizadoOlva(self $grupo)
    {
        if ($grupo->distribucion == 'OLVA') {
            $data = [
                'condicion_envio' => (($grupo->cod_recojo == 1) ? Pedido::$estadosCondicionEnvioCode[Pedido::RECOJO_MOTORIZADO_INT] : Pedido::$estadosCondicionEnvioCode[Pedido::MOTORIZADO_INT]),
                'condicion_envio_code' => (($grupo->cod_recojo == 1) ? Pedido::RECOJO_MOTORIZADO_INT : Pedido::MOTORIZADO_INT),
                'condicion_envio_at' => now(),
                'cambio_direccion_at' => null,
            ];
            $grupoolva = DireccionGrupo::query()->activo()
                ->where('condicion_envio_code', (($grupo->cod_recojo == 1) ? Pedido::RECOJO_MOTORIZADO_INT : Pedido::MOTORIZADO_INT))
                ->where('distribucion', 'OLVA')
                ->orderBy('created_at')
                ->first();
            if ($grupoolva == null) {
                $grupoolva = $grupo;
                self::cambiarCondicionEnvio($grupo, (($grupo->cod_recojo == 1) ? Pedido::RECOJO_MOTORIZADO_INT : Pedido::MOTORIZADO_INT));
            } else {
                $data['direccion_grupo'] = $grupoolva->id;
                $grupo->pedidos()->update($data);
                self::restructurarCodigos($grupo);
                self::restructurarCodigos($grupoolva);
            }
            return $grupoolva;
        } else {
            self::cambiarCondicionEnvio($grupo, (($grupo->cod_recojo == 1) ? Pedido::RECOJO_MOTORIZADO_INT : Pedido::MOTORIZADO_INT));
        }
        return $grupo;
    }

    public static function cambiarCondicionEnvio(self $grupo, int $condicion_envio, $extras = [])
    {
        $data = [
            'condicion_envio' => Pedido::$estadosCondicionEnvioCode[$condicion_envio],
            'condicion_envio_code' => $condicion_envio,
            'condicion_envio_at' => Carbon::now(),
            'cambio_direccion_at' => null,
        ];
        $grupo->update(array_merge($data, $extras));
        $grupo->pedidos()->update($data);

        if ($grupo->distribucion == 'OLVA' && $grupo->condicion_envio_code == Pedido::RECEPCIONADO_OLVA_INT) {
            self::dividirCondicionEnvioOlva($grupo);
        }

        return $grupo;
    }


    public static function dividirCondicionEnvioOlva(self $grupo)
    {
        $pgrupos = $grupo->pedidos->groupBy(fn(Pedido $pedido) => $pedido->env_zona . '_' . $pedido->env_tracking)->values();
        foreach ($pgrupos as $index => $pgrupo) {
            if ($index > 0) {
                $model = $grupo->replicate();
                $model->save();
                foreach ($pgrupo as $pedido) {
                    $pedido->update([
                        'direccion_grupo' => $model->id
                    ]);
                }
                DireccionGrupo::restructurarCodigos($model);
            }
        }
        DireccionGrupo::restructurarCodigos($grupo);
        return $grupo;
    }


    public static function addSolicitudAuthorization(self $grupo, $key = 'paquetes')
    {
        setting()->load();
        $motorizado_id = $grupo->motorizado_id;
        $key = "motorizado.authorization.ruta.$key.$motorizado_id";
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

    public static function getSolicitudAuthorization($motorizadoId, $key = 'paquetes')
    {
        setting()->load();
        $key = "motorizado.authorization.ruta.$key.$motorizadoId";
        return setting($key, []);
    }

    public static function clearSolicitudAuthorization($motorizadoId, $key = 'paquetes')
    {
        setting()->load();
        $key = "motorizado.authorization.ruta.$key.$motorizadoId";
        setting()->forget($key);
        setting()->save();
    }
}
