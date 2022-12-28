<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    /**************
     * CONSTANTES PEDIDO
     */
    const POR_ATENDER = 'POR ATENDER - OPE';//1
    const EN_PROCESO_ATENCION = 'EN PROCESO ATENCION';//2
    const ATENDIDO = 'ATENDIDO';//3
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
    const ATENDIDO_JEFE_OPE = 'ATENDIDO - JEFE OPE'; // 5
    const RECEPCION_COURIER = 'RECEPCION - COURIER'; // 12
    const CONFIRMACION_COURIER = 'CONFIRMACION - COURIER'; // 11
    const REPARTO_COURIER = 'REPARTO - COURIER'; // 8
    const SEGUIMIENTO_PROVINCIA_COURIER = 'SEGUIMIENTO PROVINCIA - COURIER'; // 9
    const MOTORIZADO = 'MOTORIZADO'; // 15
    const ENTREGADO_CLIENTE = 'ENTREGADO - CLIENTE'; // 10
    const ENTREGADO_SIN_SOBRE_OPE = 'ENTREGADO SIN SOBRE - OPE'; // 13
    const ENTREGADO_SIN_SOBRE_CLIENTE = 'ENTREGADO SIN SOBRE - CLIENTE'; // 14

    /**************
     * CONSTANTES CONDICION ENVIO NUMERICO
     */
    const POR_ATENDER_OPE_INT = 1;
    const EN_ATENCION_OPE_INT = 2;
    const ATENDIDO_OPE_INT = 3;
    const ATENDIDO_JEFE_OPE_INT = 5;
    const RECEPCION_COURIER_INT = 12;
    const CONFIRMACION_COURIER_INT = 11;
    const REPARTO_COURIER_INT = 8;
    const SEGUIMIENTO_PROVINCIA_COURIER_INT = 9;
    const MOTORIZADO_INT = 15;
    const ENTREGADO_CLIENTE_INT = 10;
    const ENTREGADO_SIN_SOBRE_OPE_INT = 13;
    const ENTREGADO_SIN_SOBRE_CLIENTE_INT = 14;

    /**************
     * FIN CONSTANTES CONDICION ENVIO NUMERICO
     */

    //envio
    const ENVIO_CONFIRMAR_RECEPCION = '1';//ENVIADO CONFIRMAR RECEPCION
    const ENVIO_RECIBIDO = '2';//ENVIADO RECIBIDO

    //condicion de envio en cadena
    const PENDIENTE_DE_ENVIO = 'PENDIENTE DE ENVIO';//1


    //condicion de envio en entero
    const PENDIENTE_DE_ENVIO_CODE = 1;
    const EN_REPARTO_CODE = 2;
    const ENTREGADO_CODE = 3;

    const PORATENDDER = 1;


    /* relacion de conciones de envio y enteros */

    public static $estadosCondicion = [
        'ANULADO' => 4,
        'POR ATENDER' => 1,
        'EN PROCESO ATENCION' => 2,
        'ATENDIDO' => 3,
    ];

    public static $estadosCondicionCode = [
        4 => 'ANULADO',
        1 => 'POR ATENDER',
        2 => 'EN PROCESOS DE ATENCION',
        3 => 'ATENDIDO',
    ];

    /******************
     * CONDICION DE ENVIO
     */

    public static $estadosCondicionEnvio = [

        'POR ATENDER - OPE' => 1,
        'EN ATENCION - OPE' => 2,
        'ATENDIDO - OPE' => 3,
        'ATENDIDO - JEFE OPE' => 5,
        'RECEPCION - COURIER' => 12,
        'CONFIRMACION - COURIER' => 11,
        'REPARTO - COURIER' => 8,
        'SEGUIMIENTO PROVINCIA - COURIER' => 9,
        'MOTORIZADO' => 15,
        'ENTREGADO - CLIENTE' => 10,
        'ENTREGADO SIN SOBRE - OPE' => 13,
        'ENTREGADO SIN SOBRE - CLIENTE' => 14

    ];

    public static $estadosCondicionEnvioCode = [

        1 => 'POR ATENDER - OPE',
        2 => 'EN ATENCION - OPE',
        3 => 'ATENDIDO - OPE',
        5 => 'ATENDIDO - JEFE OPE',
        12 => 'RECEPCION - COURIER',
        11 => 'CONFIRMACION - COURIER',
        8 => 'REPARTO - COURIER',
        9 => 'SEGUIMIENTO PROVINCIA - COURIER',
        15 => 'MOTORIZADO',
        10 => 'ENTREGADO - CLIENTE',
        13 => 'ENTREGADO SIN SOBRE - OPE',
        14 => 'ENTREGADO SIN SOBRE - CLIENTE'
    ];


    protected $guarded = ['id'];

    protected $dates = [
        'fecha_anulacion',
        'fecha_anulacion_confirm',
        'fecha_anulacion_denegada',
    ];
    protected $appends=[
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

    public function detallePedidos()
    {
        return $this->hasMany(DetallePedido::class);
    }

    public function pagoPedidos()
    {
        return $this->hasMany(PagoPedido::class);
    }

    public function getCondicionEnvioColorAttribute()
    {
        $condicion_envio = \Str::lower($this->condicion_envio??'');

        if (\Str::contains($condicion_envio, "ope")) {
            return '#ffc107';
        } elseif (\Str::contains($condicion_envio, "courier") || \Str::contains($condicion_envio, "motorizado")) {
            return '#f97100';
        } elseif (\Str::contains($condicion_envio, "cliente")) {
            return '#b0deb3';
        }else{
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

    public function scopeActivo($query, $status = '1')
    {
        return $query->where('pedidos.estado', '=', $status);
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
        return $query->whereIn($this->qualifyColumn('u.identificador'),  $usersasesores);
    }

    public function scoperoljefedellamada($query)
    {       
        return $query;
    }

    public function scoperolasesor($query)
    {
        $usersasesores = User::whereIn('users.rol', ['Asesor','Administrador','ASESOR ADMINISTRATIVO'])
                ->where('users.estado', '1')
                ->where('users.identificador', Auth::user()->identificador)
                ->select(
                    DB::raw("users.identificador as identificador")
                )
                ->pluck('users.identificador');
        return $query->whereIn($this->qualifyColumn('u.identificador'),  $usersasesores);
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
        return $query->whereIn($this->qualifyColumn('u.identificador'),  $usersasesores);
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
                return $query->whereIn($this->qualifyColumn('user_id'), User::query()->select('id')->activo()->where('users.supervisor', auth()->id()));
            }
        }
        if (in_array(User::ROL_ASESOR, $roles)) {
            if (auth()->user()->rol == User::ROL_ASESOR) {
                return $query->where($this->qualifyColumn('user_id'), '=', auth()->id());
            }
        }
        return $query;
    }
}
