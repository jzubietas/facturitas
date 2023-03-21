<?php

namespace App\Models;

use App\Traits\CommonModel;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    use CommonModel;

    /**************
     * CONSTANTES PEDIDO
     */
    const POR_ATENDER = 'POR ATENDER - OPE';//1
    const EN_PROCESO_ATENCION = 'EN ATENCION - OPE';//2
    const ATENDIDO = 'ATENDIDO - OPE';//3
    const ANULADO = 'ANULADO';//4
    const PENDIENTE_ANULACION = 'PENDIENTE ANULACION';//
    const PENDIENTE_ANULACION_PARCIAL = 'PTE. ANULACION PARCIAL';//
    const ANULADO_PARCIAL = 'ANULADO PARCIAL';//5
    /**************
     * CONSTANTES PEDIDO NUMERICO
     */
    const POR_ATENDER_INT = 1;
    const EN_PROCESO_ATENCION_INT = 2;
    const ATENDIDO_INT = 3;
    const ANULADO_INT = 4;
    const ANULADO_PARCIAL_INT = 5;
    /**************
     * CONSTANTES CONDICION ENVIO
     */
    const POR_ATENDER_OPE_INT = 1;
    const POR_ATENDER_OPE = 'POR ATENDER - OPE'; // 1
    const EN_ATENCION_OPE_INT = 2;
    const EN_ATENCION_OPE = 'EN ATENCION - OPE'; // 2
    const ATENDIDO_OPE_INT = 3;
    const ATENDIDO_OPE = 'ATENDIDO - OPE'; // 3

    const ENVIADO_OPE_INT = 5;
    const ENVIADO_OPE = 'ENVIADO A JEFE OPE - OPE';//5
    const RECIBIDO_JEFE_OPE_INT = 6;
    const RECIBIDO_JEFE_OPE = 'RECIBIDO - JEFE OPE'; // 6
    const ENVIO_COURIER_JEFE_OPE_INT = 12;
    const ENVIO_COURIER_JEFE_OPE = 'ENVIO A COURIER - JEFE OPE'; // 12
    const RECEPCION_COURIER_INT = 11;
    const RECEPCION_COURIER = 'RECEPCION - COURIER'; // 11
    const REPARTO_COURIER_INT = 8;
    const REPARTO_COURIER = 'REPARTO - COURIER'; // 8
    const MOTORIZADO_INT = 15;

    const MOTORIZADO = 'MOTORIZADO'; // 15
    const ENTREGADO_CLIENTE_INT = 10;
    const ENTREGADO_CLIENTE = 'ENTREGADO - CLIENTE'; // 10
    const ENTREGADO_SIN_SOBRE_OPE_INT = 13;
    const ENTREGADO_SIN_SOBRE_OPE = 'ATENDIDO: ENTREGADO SIN SOBRE - OPE'; // 13
    const ENTREGADO_SIN_SOBRE_CLIENTE_INT = 14;
    const ENTREGADO_SIN_SOBRE_CLIENTE = 'ENTREGADO SIN SOBRE - CLIENTE'; // 14
    const CONFIRM_MOTORIZADO_INT = 16;
    const CONFIRM_MOTORIZADO = 'PRE* ENTREGADO A CLIENTE - MOTORIZADO';
    const CONFIRM_VALIDADA_CLIENTE_INT = 17;
    const CONFIRM_VALIDADA_CLIENTE = 'CONFIRMACION VALIDADA - CLIENTE'; // 17
    const RECEPCION_MOTORIZADO_INT = 18;
    const RECEPCION_MOTORIZADO = 'RECEPCION - MOTORIZADO'; // 18
    const ENVIO_MOTORIZADO_COURIER_INT = 19; // 19
    const ENVIO_MOTORIZADO_COURIER = 'ENVIO A MOTORIZADO - COURIER'; // 19
    const RECEPCIONADO_OLVA_INT = 9;
    const RECEPCIONADO_OLVA = 'RECEPCIONADO - OLVA'; // 9
    const EN_CAMINO_OLVA_INT = 22;
    const EN_CAMINO_OLVA = 'EN CAMINO - OLVA'; // 22
    const EN_TIENDA_AGENTE_OLVA_INT = 23;
    const EN_TIENDA_AGENTE_OLVA = 'EN TIENDA/AGENTE - OLVA';//23
    const NO_ENTREGADO_OLVA_INT = 24;
    const NO_ENTREGADO_OLVA = 'NO ENTREGADO - OLVA';//24
    const ENTREGADO_PROVINCIA_INT = 21; // 19
    const ENTREGADO_PROVINCIA = 'ENTREGADO - PROVINCIA'; // 21
    const ENTREGADO_SIN_ENVIO_CLIENTE_INT = 25;
    const ENTREGADO_SIN_ENVIO_CLIENTE = 'ENTREGADO SIN ENVIO - CLIENTE'; // 25
    const RECOJO_COURIER_INT = 26;
    const RECOJO_COURIER = 'RECOJO - COURIER';//26
    const REPARTO_RECOJO_COURIER_INT = 27;
    const REPARTO_RECOJO_COURIER = 'REPARTO RECOJO - COURIER';//27
    const ENVIO_RECOJO_MOTORIZADO_COURIER_INT = 28;
    const ENVIO_RECOJO_MOTORIZADO_COURIER = 'ENVIO RECOJO A MOTORIZADO - COURIER'; //28
    const RECEPCION_RECOJO_MOTORIZADO_INT = 29;
    const RECEPCION_RECOJO_MOTORIZADO = 'RECEPCION RECOJO - MOTORIZADO';//29
    const RECOJO_MOTORIZADO_INT = 30;
    const RECOJO_MOTORIZADO = 'RECOJO MOTORIZADO'; // 30
    const RECIBIDO_RECOJO_CLIENTE_INT = 31;
    const RECIBIDO_RECOJO_CLIENTE = 'RECIBIDO RECOJO - CLIENTE';
    const CONFIRMAR_RECOJO_MOTORIZADO_INT = 32;
    const CONFIRMAR_RECOJO_MOTORIZADO = 'CONFIRMAR RECIBIDO  RECOJO - MOTORIZADO'; //32 esta en courier
    const ENTREGADO_RECOJO_COURIER_INT = 33;
    const ENTREGADO_RECOJO_COURIER = 'ENTREGADO RECOJO - COURIER'; //34
    const ENTREGADO_RECOJO_JEFE_OPE_INT = 34;
    const ENTREGADO_RECOJO_JEFE_OPE = 'ENTREGADO RECOJO - JEFE OPE'; //34

    const CORRECCION_OPE_INT = 35;
    const CORRECCION_OPE = 'CORRECCION - OPE'; // 35


    /*  cosnt RECOJO CLIENTE MOTORIZADO

    cosnt RECOJO CLIENTE CONFIRMAR MOTORIZADO*/

    /**************
     * CONSTANTES CONDICION ENVIO NUMERICO
     */


    const ESTADO_MOTORIZADO_OBSERVADO = 1;
    const ESTADO_MOTORIZADO_NO_CONTESTO = 2;
    const ESTADO_MOTORIZADO_NO_RECIBIDO = 3;
    const ESTADO_MOTORIZADO_RE_RECIBIDO = 4;
    const color_skype_blue = '#abc4ff';
    const color_blue = '#031d44';
    const colo_progress_bar = '#73d9bc';

    /**************
     * FIN CONSTANTES CONDICION ENVIO NUMERICO
     */

    public static $estadosCondicion = [
        'ANULADO' => 4,
        'POR ATENDER' => 1,
        'EN PROCESO ATENCION' => 2,
        'ATENDIDO' => 3,
    ];

    public static $estadosCondicionEnvioCode = [
        self::POR_ATENDER_OPE_INT => self::POR_ATENDER_OPE,
        self::EN_ATENCION_OPE_INT => self::EN_ATENCION_OPE,
        self::ATENDIDO_OPE_INT => self::ATENDIDO_OPE,
        self::ENVIADO_OPE_INT => self::ENVIADO_OPE,
        self::RECIBIDO_JEFE_OPE_INT => self::RECIBIDO_JEFE_OPE,
        self::ENVIO_COURIER_JEFE_OPE_INT => self::ENVIO_COURIER_JEFE_OPE,
        self::RECEPCION_COURIER_INT => self::RECEPCION_COURIER,
        self::REPARTO_COURIER_INT => self::REPARTO_COURIER,
        self::RECEPCIONADO_OLVA_INT => self::RECEPCIONADO_OLVA,
        self::MOTORIZADO_INT => self::MOTORIZADO,
        self::ENTREGADO_CLIENTE_INT => self::ENTREGADO_CLIENTE,
        self::ENTREGADO_SIN_SOBRE_OPE_INT => self::ENTREGADO_SIN_SOBRE_OPE,
        self::ENTREGADO_SIN_SOBRE_CLIENTE_INT => self::ENTREGADO_SIN_SOBRE_CLIENTE,
        self::CONFIRM_MOTORIZADO_INT => self::CONFIRM_MOTORIZADO,
        self::CONFIRM_VALIDADA_CLIENTE_INT => self::CONFIRM_VALIDADA_CLIENTE,
        self::RECEPCION_MOTORIZADO_INT => self::RECEPCION_MOTORIZADO,
        self::ENVIO_MOTORIZADO_COURIER_INT => self::ENVIO_MOTORIZADO_COURIER,
        self::ENTREGADO_PROVINCIA_INT => self::ENTREGADO_PROVINCIA,
        self::EN_CAMINO_OLVA_INT => self::EN_CAMINO_OLVA,
        self::EN_TIENDA_AGENTE_OLVA_INT => self::EN_TIENDA_AGENTE_OLVA,
        self::NO_ENTREGADO_OLVA_INT => self::NO_ENTREGADO_OLVA,
        self::ENTREGADO_SIN_ENVIO_CLIENTE_INT => self::ENTREGADO_SIN_ENVIO_CLIENTE,
        self::REPARTO_RECOJO_COURIER_INT => self::REPARTO_RECOJO_COURIER,
        self::RECEPCION_RECOJO_MOTORIZADO_INT => self::RECEPCION_RECOJO_MOTORIZADO,
        self::RECOJO_MOTORIZADO_INT => self::RECOJO_MOTORIZADO,
        self::RECIBIDO_RECOJO_CLIENTE_INT => self::RECIBIDO_RECOJO_CLIENTE,
        self::CONFIRMAR_RECOJO_MOTORIZADO_INT => self::CONFIRMAR_RECOJO_MOTORIZADO,
        self::ENTREGADO_RECOJO_COURIER_INT => self::ENTREGADO_RECOJO_COURIER,
        self::ENTREGADO_RECOJO_JEFE_OPE_INT => self::ENTREGADO_RECOJO_JEFE_OPE,
    ];

    protected $guarded = ['id'];

    protected $dates = [
        'fecha_anulacion',
        'fecha_anulacion_confirm',
        'fecha_anulacion_denegada',
        'cambio_direccion_at',
        'fecha_recepcion_courier',
        'courier_sync_at',
        'courier_failed_sync_at',
    ];
    protected $appends = [
        'condicion_envio_color'
    ];

    protected $fillable = [
        'id',
        'correlativo',
        'cliente_id',
        'user_id',
        'identificador',
        'exidentificador',
        'icelular_asesor',
        'celular_cliente',
        'icelular_cliente',
        'creador',
        'pago',
        'pagado',
        'destino',
        'trecking',
        'direccion',
        'condicion_envio',
        'condicion_envio_code',
        'condicion_envio_at',
        'condicion',
        'condicion_code',
        'condicion_int',
        'codigo',
        'codigos_confirmados',
        'notificacion',
        'motivo',
        'responsable',
        'modificador',
        'devuelto',
        'cant_devuelto',
        'observacion_devuelto',
        'estado',
        'da_confirmar_descarga',
        'sustento_adjunto',
        'path_adjunto_anular',
        'path_adjunto_anular_disk',
        'pendiente_anulacion',
        'user_anulacion_id',
        'fecha_anulacion',
        'fecha_anulacion_confirm',
        'fecha_anulacion_denegada',
        'created_at',
        'updated_at',
        'returned_at',
        'cambio_direccion_at',
        'envio',
        'estado_condicion_envio',
        'estado_condicion_pedido',
        'estado_sobre',
        'estado_consinsobre',
        'env_destino',
        'env_distrito',
        'env_zona',
        'env_zona_asignada',
        'env_nombre_cliente_recibe',
        'env_celular_cliente_recibe',
        'env_cantidad',
        'env_direccion',
        'env_tracking',
        'env_referencia',
        'env_numregistro',
        'env_rotulo',
        'env_observacion',
        'env_gmlink',
        'env_importe',
        'estado_ruta',
        'direccion_grupo',
        'fecha_salida',
        'cambio_direccion_sustento',
        'fecha_recepcion_courier',
        'fecha_envio_op_courier',
        'fecha_envio_atendido_op',
        'pedido_scaneo',
        'codigo_regularizado',
        'courier_sync_at',
        'courier_failed_sync_at',
        'courier_sync_finalized',
        'courier_estado',
        'courier_data',
        'estado_correccion',
        'condicion_envio_anterior',
        'condicion_envio_code_anterior',
        'codigo_anterior',
        'pedidoid_anterior',
        'resultado_correccion',
        'env_sustento'
    ];

    /* public function user()
    {
        return $this->belongsTo('App\Models\User');
    } */

    protected $casts = [
        'courier_estado' => 'boolean',
        'courier_data' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function imagenAtencion()
    {
        return $this->hasMany(ImagenAtencion::class, 'pedido_id');
    }

    public function detallePedido()
    {
        return $this->hasOne(DetallePedido::class)->activo();
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function pagoPedidos()
    {
        return $this->hasMany(PagoPedido::class);
    }

    public function direcciongrupo()
    {
        return $this->belongsTo(DireccionGrupo::class, 'direccion_grupo');
    }

    public function grupoPedidos()
    {
        return $this->belongsToMany(GrupoPedido::class, 'grupo_pedido_items')->withPivot([
            'razon_social',
            'codigo',
        ])->orderByPivot('razon_social', 'asc');
    }

    public function getCondicionEnvioColorAttribute()
    {
        $condicion_envio = \Str::lower($this->condicion_envio ?? '');
        return self::getColorByCondicionEnvio($condicion_envio);
    }

    public function getIdCodeAttribute()
    {
        return generate_correlativo('PED', $this->id, 4);
    }

    public static function generateIdCode($id)
    {
        return generate_correlativo('PED', $id, 4);
    }

    public function notasCreditoFiles()
    {
        $data = setting("pedido." . $this->id . ".nota_credito_file");
        if (is_array($data)) {
            return $data;
        }
        return [];
    }

    public function adjuntosFiles()
    {
        $data = setting("pedido." . $this->id . ".adjuntos_file");
        if (is_array($data)) {
            return $data;
        }
        return [];
    }


    public function scopeSinZonaAsignadaEnvio($query)
    {
        return $query->where(function ($query) {
            $query->whereNull($this->qualifyColumn('env_zona_asignada'));
            $query->orWhere($this->qualifyColumn('env_zona_asignada'), '=', '');
        });
    }

    public function scopeZonaAsignadaEnvio($query, $zona)
    {
        return $query->where($this->qualifyColumn('env_zona_asignada'), '=', $zona);
    }

    public function scopeConDireccionEnvio($query)
    {
        return $query->where($this->qualifyColumn('estado_sobre'), '=', 1);
    }

    public function scopeSinDireccionEnvio($query)
    {
        return $query->where($this->qualifyColumn('estado_sobre'), '=', 0);
    }

    public function scopePagados($query)
    {
        return $query->where('pedidos.pago', '=', 1)->where('pedidos.pagado', '=', 2);
    }

    public function scopeNoPagados($query)
    {
        return $query->whereIn('pedidos.pago', [0, 1])//no hay pago
        ->whereIn('pedidos.pagado', [0, 1]);//no hay pago o adelanto
    }

    public function scopeAtendidos($query)
    {
        return $query->where($this->qualifyColumn('condicion_envio_code'), '=', self::ATENDIDO_INT);
    }

    public function scopePorAtender($query)
    {
        return $query->where($this->qualifyColumn('condicion_envio_code'), '=', self::POR_ATENDER_INT);
    }

    public function scopePorAtenderEstatus($query)
    {
        return $query->whereIn($this->qualifyColumn('condicion_envio_code'), [self::POR_ATENDER_INT, self::EN_PROCESO_ATENCION_INT]);
    }

    public function scopePendienteAnulacion($query)
    {
        return $query->whereIn($this->qualifyColumn('pendiente_anulacion'), [1]);
    }

    public function scopeAsesoresDeOperarios()
    {

    }

    public function scopeImporte($query, $importe)
    {
        return $query->where($this->qualifyColumn('env_importe'), '=', $importe);
    }

    public function scopeGmlink($query, $gmLink)
    {
        return $query->where($this->qualifyColumn('env_gmlink'), '=', $gmLink);
    }

    public function scopeObservacion($query, $observacion)
    {
        return $query->where($this->qualifyColumn('env_observacion'), '=', $observacion);
    }

    public function scopeRotulo($query, $rotulo)
    {
        return $query->where($this->qualifyColumn('env_rotulo'), '=', $rotulo);
    }

    public function scopeNumregistro($query, $numRegistro)
    {
        return $query->where($this->qualifyColumn('env_numregistro'), '=', $numRegistro);
    }

    public function scopeReferencia($query, $referencia)
    {
        return $query->where($this->qualifyColumn('env_referencia'), '=', $referencia);
    }

    public function scopeTracking($query, $tracking)
    {
        return $query->where($this->qualifyColumn('env_tracking'), '=', $tracking);
    }

    public function scopeDireccion($query, $direction)
    {
        $query->where('env_direccion', '=', $direction);
    }

    public function scopeCantidad($query, $cantidad)
    {
        return $query->where($this->qualifyColumn('env_cantidad'), '=', $cantidad);
    }

    public function scopeCelularClienteRecibe($query, $celularClienteRecibe)
    {
        return $query->where($this->qualifyColumn('env_celular_cliente_recibe'), '=', $celularClienteRecibe);
    }

    public function scopeNombreClienteRecibe($query, $nombredecliente)
    {
        $query->where('env_nombre_cliente_recibe', '=', $nombredecliente);
    }

    public function scopeZonaAsignada($query)
    {
        $query->where('env_zona', '=', 'OLVA');
    }

    public function scopeDistrito($query, $distrito)
    {
        $query->where('env_distrito', '=', $distrito);
    }

    public function scopeDestino($query, $destino)
    {
        $query->where('env_destino', '=', $destino);
    }

    public function scoperoladmin($query)
    {
        return $query;
        //return $query->where($this->qualifyColumn('condicion_envio_code'), '=', self::ATENDIDO_INT);
    }

    public function scoperolllamada($query)
    {
        $usersasesores = User::where('users.rol', 'Asesor')
            ->where('users.estado', '1')
            ->where('users.llamada', Auth::user()->id)
            ->select(
                DB::raw("users.identificador as identificador")
            )
            ->pluck('users.identificador');
        return $query->whereIn($this->qualifyColumn('u.identificador'), $usersasesores);
    }

    public function scoperoljefedellamada($query)
    {
        return $query;
    }

    public function scoperolasesor($query)
    {
        $usersasesores = User::whereIn('users.rol', ['Asesor', 'Administrador', 'ASESOR ADMINISTRATIVO'])
            ->where('users.estado', '1')
            ->where('users.identificador', Auth::user()->identificador)
            ->select(
                DB::raw("users.identificador as identificador")
            )
            ->pluck('users.identificador');
        return $query->whereIn($this->qualifyColumn('u.identificador'), $usersasesores);
    }

    public function scoperolencargado($query)
    {
        $usersasesores = User::where('users.rol', 'Asesor')
            ->where('users.estado', '1')
            ->where('users.supervisor', Auth::user()->id)
            ->select(
                DB::raw("users.identificador as identificador")
            )
            ->pluck('users.identificador');
        return $query->whereIn($this->qualifyColumn('u.identificador'), $usersasesores);
    }

    public function scopeCurrentUser($query)
    {
        return $query->where($this->qualifyColumn('user_id'), '=', auth()->id());
    }

    public function scopeNoPendingAnulation($query)
    {
        return $query->where($this->qualifyColumn('pendiente_anulacion'), '<>', '1');
    }

    /**
     * @param Pedido $pedido
     * @return \Illuminate\Contracts\Foundation\Application|mixed|\setting|(\setting&\Illuminate\Contracts\Foundation\Application)
     * text: pedido.[pedido_id].adjuntos_file.[index]
     * text: pedido.[pedido_id].adjuntos_disk.[index]
     */
    public static function getAnuladosAdjuntos(self $pedido)
    {
        return setting("pedido." . $pedido->id);
    }

    /**
     * @param Builder $query
     * @param $roles
     */
    public function scopeSegunRolUsuario($query, $roles = [])
    {
        if (in_array(User::ROL_ADMIN, $roles)) {
            if (auth()->user()->rol == User::ROL_ADMIN) {
                return $query;
            }
        }
        if (in_array(User::ROL_ENCARGADO, $roles)) {
            if (auth()->user()->rol == User::ROL_ENCARGADO) {
                return $query->whereIn(
                    $this->qualifyColumn('user_id'),
                    User::query()->select('id')->activo()->where('users.supervisor', auth()->id())
                );
            }
        }

        if (in_array(User::ROL_LLAMADAS, $roles)) {
            if (auth()->user()->rol == User::ROL_LLAMADAS) {
                return $query->whereIn(
                    $this->qualifyColumn('user_id'),
                    User::query()->select('id')->activo()->where('users.llamada', auth()->id())
                );
            }
        }

        if (in_array(User::ROL_OPERARIO, $roles)) {
            if (auth()->user()->rol == User::ROL_OPERARIO) {
                return $query->whereIn(
                    $this->qualifyColumn('user_id'),
                    User::query()->select('id')->activo()->where('users.operario', auth()->id())
                );
            }
        }

        if (in_array(User::ROL_ASESOR, $roles)) {
            if (auth()->user()->rol == User::ROL_ASESOR) {
                return $query->where($this->qualifyColumn('user_id'), '=', auth()->id());
            }
        }

        if (in_array(User::ROL_JEFE_LLAMADAS, $roles)) {
            return $query;
        }


        if (in_array(User::ROL_JEFE_OPERARIO, $roles)) {
            if (auth()->user()->rol == User::ROL_JEFE_OPERARIO) {

                return $query->where($this->qualifyColumn('user_id'), '=', auth()->id());
            }
        }

        if (in_array(User::ROL_ASESOR_ADMINISTRATIVO, $roles)) {
            if (auth()->user()->rol == User::ROL_ASESOR_ADMINISTRATIVO) {
                return $query->where($this->qualifyColumn('user_id'), '=', auth()->id());
            }
        }


        return $query;
    }

    public function scopeConsultarecojo($query, $celularClienteRecibe, $cantidad, $tracking, $referencia, $numRegistro, $rotulo, $observacion, $gmLink, $importe, $zona, $destino, $direction, $nombredecliente, $distrito)
    {
        $query = $query
            ->zonaAsignadaEnvio($zona)
            ->destino($destino)
            ->distrito($distrito)
            ->nombreClienteRecibe($nombredecliente)
            ->celularClienteRecibe($celularClienteRecibe)
            ->cantidad($cantidad)
            ->direccion($direction)
            ->tracking($tracking)
            ->referencia($referencia)
            ->numregistro($numRegistro)
            ->rotulo($rotulo)
            ->observacion($observacion)
            ->gmlink($gmLink)
            ->importe($importe);
        return $query;
    }

    public static function getColorByCondicionEnvio($condicion_envio)
    {
        $condicion_envio = \Str::lower($condicion_envio ?? '');

        if (\Str::contains($condicion_envio, "olva") || $condicion_envio == \Str::lower(Pedido::ENTREGADO_PROVINCIA)) {
            return '#ffe007';
        } elseif (\Str::contains($condicion_envio, "ope")) {
            if (\Str::contains($condicion_envio, "recojo")) {
                return '#E7C6FF';
            } else {
                return '#23cafd';
            }
        } elseif (\Str::contains($condicion_envio, "courier")) {
            if (\Str::contains($condicion_envio, "recojo")) {
                return '#E7C6FF';
            } else {
                return '#f97100';
            }

        } elseif (\Str::contains($condicion_envio, "motorizado")) {
            if (\Str::contains($condicion_envio, "recojo")) {
                return '#E7C6FF';
            } else {
                return '#f97100';
            }

        } elseif (\Str::contains($condicion_envio, "cliente")) {
            if (\Str::contains($condicion_envio, "recojo")) {
                return '#E7C6FF';
            } else {
                return '#b0deb3';
            }
        } elseif (\Str::contains($condicion_envio, "recojo")) {
            return '#E7C6FF';
        } else {
            return '#b0deb3';
        }
    }
}
