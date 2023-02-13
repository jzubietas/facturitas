<?php

namespace App\Models;

use App\Traits\CommonModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    use CommonModel;

    const ANULADO = 'ANULADO';
    const PAGO = 'PAGO';
    const ABONADO = 'ABONADO';
    const ADELANTO = 'ADELANTO';
    const OBSERVADO = 'OBSERVADO';
    const PENDIENTE = 'PENDIENTE';
    const ABONADO_PARCIAL = 'ABONADO_PARCIAL';


    const SUBCONDICION_COURIER_PERDONADO = "COURIER PERDONADO";
    const SUBCONDICION_DEUDA_PERDONADA = "DEUDA PERDONADA";

    const ANULADO_CODE = 0;
    const PAGO_CODE = 1;
    const ABONADO_CODE = 2;
    const ADELANTO_CODE = 4;
    const OBSERVADO_CODE = 6;
    const PENDIENTE_CODE = 5;
    const ABONADO_PARCIAL_CODE = 3;

    public static $migrateCondiciones = [
        'ANULADO' => 0,
        'PAGO' => 1,
        'ABONADO' => 2,
        'ABONADO_PARCIAL' => 3,
        'ADELANTO' => 4,
        'PENDIENTE' => 5,
        'OBSERVADO' => 6,
    ];

    public static $migrateSubCondiciones = [
        'COURIER PERDONADO' => 1,
        'DEUDA PERDONADA' => 2,
    ];

    protected $guarded = ['id'];

    public function getIdCodeAttribute(): string
    {
        return generate_correlativo_pago('PAG', $this->id, 4);
    }
    public function scopeCondicion($query, $value)
    {
        $query->where('condicion', '=', $value);
    }

    public function setCondicionAttribute($value)
    {
        $this->attributes['condicion'] = $value;
        $this->setAttribute('condicion_code', self::$migrateCondiciones[$value] ?? $value);
    }

    public function setSubcondicionAttribute($value)
    {
        $this->attributes['subcondicion'] = $value;
        $this->setAttribute('subcondicion_code', self::$migrateSubCondiciones[$value] ?? $value);
    }

    public function getCodeIdAttribute()
    {
        //$cantidadvoucher = $this->detalle_pagos()->whereEstado(1)->count();
        //$cantidadpedido = $this->pago_pedidos()->whereEstado(1)->count();
        $users = $this->user->identificador;

        $fecha_created=Carbon::parse($this->created_at);
        $dd=$fecha_created->format('d');
        $mm=$fecha_created->format('m');
        $unido=$dd.$mm;

        //$unido = ($cantidadvoucher > 1) ? 'V' : 'I' . (($cantidadpedido > 1) ? 'V' : 'I');
        if ($this->id < 10) {
            return 'PAG' . $users . '-' . $unido . '-' . $this->id;
        } else if ($this->id < 100) {
            return 'PAG00' . $users . '-' . $unido . '-' . $this->id;
        } else if ($this->id < 1000) {
            return 'PAG0' . $users . '-' . $unido . '-' . $this->id;
        } else {
            return 'PAG' . $users . '-' . $unido . '-' . $this->id;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalle_pagos()
    {
        return $this->hasMany(DetallePago::class, 'pago_id');
    }

    public function pago_pedidos()
    {
        return $this->hasMany(PagoPedido::class, 'pago_id');
    }

    public function pedidos()
    {

    }

    public function isAbonado()
    {
        return $this->condicion == self::ABONADO;
    }

    public function isAdelanto()
    {
        return $this->condicion == self::ADELANTO;
    }

    public function isObservado()
    {
        return $this->condicion == self::OBSERVADO;
    }

    public function isPago()
    {
        return $this->condicion == self::PAGO;
    }

    public function isPendiente()
    {
        return $this->condicion == self::PENDIENTE;
    }

    public function isAbonadoParcial()
    {
        return $this->condicion == self::ABONADO_PARCIAL;
    }


}
