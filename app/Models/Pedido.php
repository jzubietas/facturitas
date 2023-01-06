<?php

namespace App\Models;

use App\Traits\CommonModel;
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

    /**************
     * CONSTANTES PEDIDO NUMERICO
     */
    const POR_ATENDER_INT = 1;
    const EN_PROCESO_ATENCION_INT = 2;
    const ATENDIDO_INT = 3;
    const ANULADO_INT = 4;

    /**************
     * CONSTANTES CONDICION ENVIO
     */

    const POR_ATENDER_OPE = 'POR ATENDER - OPE'; // 1
    const EN_ATENCION_OPE = 'EN ATENCION - OPE'; // 2
    const ATENDIDO_OPE = 'ATENDIDO - OPE'; // 3
    const ENVIADO_OPE = 'ENVIADO A JEFE OPE - OPE'; // 5 // ENVIADO A JEFE OPE - OPE //ENVIADO - OPE
    const RECIBIDO_JEFE_OPE = 'RECIBIDO - JEFE OPE'; // 6
    const ENVIO_COURIER_JEFE_OPE = 'ENVIO A COURIER - JEFE OPE'; // 12
    const RECEPCION_COURIER = 'RECEPCION - COURIER'; // 11
    const REPARTO_COURIER = 'REPARTO - COURIER'; // 8
    const SEGUIMIENTO_PROVINCIA_COURIER = 'SEGUIMIENTO PROVINCIA - COURIER'; // 9
    const MOTORIZADO = 'MOTORIZADO'; // 15
    const ENTREGADO_CLIENTE = 'ENTREGADO - CLIENTE'; // 10
    const ENTREGADO_SIN_SOBRE_OPE = 'ATENDIDO: ENTREGADO SIN SOBRE - OPE'; // 13
    const ENTREGADO_SIN_SOBRE_CLIENTE = 'ENTREGADO SIN SOBRE - CLIENTE'; // 14
    const CONFIRM_MOTORIZADO = 'CONFIRMACION - MOTORIZADO'; // 16
    const CONFIRM_VALIDADA_CLIENTE = 'CONFIRMACION VALIDADA - CLIENTE'; // 17
    const RECEPCION_MOTORIZADO = 'RECEPCION - MOTORIZADO'; // 18
    const ENVIO_MOTORIZADO_COURIER = 'ENVIO A MOTORIZADO - COURIER'; // 19

    /**************
     * CONSTANTES CONDICION ENVIO NUMERICO
     */
    const POR_ATENDER_OPE_INT = 1;
    const EN_ATENCION_OPE_INT = 2;
    const ATENDIDO_OPE_INT = 3;
    const ENVIADO_OPE_INT = 5;
    const RECIBIDO_JEFE_OPE_INT = 6;
    const ENVIO_COURIER_JEFE_OPE_INT = 12;
    const RECEPCION_COURIER_INT = 11;
    const REPARTO_COURIER_INT = 8;
    const SEGUIMIENTO_PROVINCIA_COURIER_INT = 9;
    const MOTORIZADO_INT = 15;
    const ENTREGADO_CLIENTE_INT = 10;
    const ENTREGADO_SIN_SOBRE_OPE_INT = 13;
    const ENTREGADO_SIN_SOBRE_CLIENTE_INT = 14;
    const CONFIRM_MOTORIZADO_INT = 16;
    const CONFIRM_VALIDADA_CLIENTE_INT = 17;
    const RECEPCION_MOTORIZADO_INT = 18;
    const ENVIO_MOTORIZADO_COURIER_INT = 19; // 19

    /**************
     * FIN CONSTANTES CONDICION ENVIO NUMERICO
     */

    public static $estadosCondicion = [
        'ANULADO' => 4,
        'POR ATENDER' => 1,
        'EN PROCESO ATENCION' => 2,
        'ATENDIDO' => 3,
    ];

    public static $estadosCondicionCode = [
        4 => 'ANULADO',
        1 => 'POR ATENDER - OPE',
        2 => 'EN ATENCION - OPE',
        3 => 'ATENDIDO - OPE',
    ];

    /******************
     * CONDICION DE ENVIO
     */

    public static $estadosCondicionEnvio = [

        'POR ATENDER - OPE' => 1,
        'EN ATENCION - OPE' => 2,
        'ATENDIDO - OPE' => 3,
        'ENVIADO - OPE' => 5,
        'RECIBIDO - JEFE OPE' => 6,
        'ENVIO A COURIER - JEFE OPE' => 12,
        'RECEPCION - COURIER' => 11,
        'REPARTO - COURIER' => 8,
        'SEGUIMIENTO PROVINCIA - COURIER' => 9,
        'MOTORIZADO' => 15,
        'ENTREGADO - CLIENTE' => 10,
        'ENTREGADO SIN SOBRE - OPE' => 13,
        'ENTREGADO SIN SOBRE - CLIENTE' => 14

    ];

    public static $estadosCondicionEnvioCode = [
        1 => self::POR_ATENDER_OPE,
        2 => self::EN_ATENCION_OPE,
        3 => self::ATENDIDO_OPE,
        5 => self::ENVIADO_OPE,
        6 => self::RECIBIDO_JEFE_OPE,
        12 => self::ENVIO_COURIER_JEFE_OPE,
        11 => self::RECEPCION_COURIER,
        8 => self::REPARTO_COURIER,
        9 => self::SEGUIMIENTO_PROVINCIA_COURIER,
        15 => self::MOTORIZADO,
        10 => self::ENTREGADO_CLIENTE,
        13 => self::ENTREGADO_SIN_SOBRE_OPE,
        14 => self::ENTREGADO_SIN_SOBRE_CLIENTE,
        16 => self::CONFIRM_MOTORIZADO,
        17 => self::CONFIRM_VALIDADA_CLIENTE,
        18 => self::RECEPCION_MOTORIZADO,
    ];


    protected $guarded = ['id'];

    protected $dates = [
        'fecha_anulacion',
        'fecha_anulacion_confirm',
        'fecha_anulacion_denegada',
    ];
    protected $appends = [
        'condicion_envio_color'
    ];

    /* public function user()
    {
        return $this->belongsTo('App\Models\User');
    } */

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
        return $this->hasOne(DetallePedido::class);
    }

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function pagoPedidos()
    {
        return $this->hasMany(PagoPedido::class);
    }

    public function direccionGrupo()
    {
        return $this->belongsTo(DireccionGrupo::class,'direccion_grupo');
    }
    public function direccionGrupos()
    {
        return $this->hasMany(DireccionGrupo::class)->activo();
    }

    public function getCondicionEnvioColorAttribute()
    {
        $condicion_envio = \Str::lower($this->condicion_envio ?? '');

        if (\Str::contains($condicion_envio, "ope")) {
            return '#ffc107';
        } elseif (\Str::contains($condicion_envio, "courier") || \Str::contains($condicion_envio, "motorizado")) {
            return '#f97100';
        } elseif (\Str::contains($condicion_envio, "cliente")) {
            return '#b0deb3';
        } else {
            return '#b0deb3';
        }
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

    public static function getColorByCondicionEnvio($condicion_envio)
    {
        $condicion_envio = \Str::lower($condicion_envio ?? '');

        if (\Str::contains($condicion_envio, "ope")) {
            return '#ffc107';
        } elseif (\Str::contains($condicion_envio, "courier") || \Str::contains($condicion_envio, "motorizado")) {
            return '#f97100';
        } elseif (\Str::contains($condicion_envio, "cliente")) {
            return '#b0deb3';
        } else {
            return '#b0deb3';
        }
    }
}
