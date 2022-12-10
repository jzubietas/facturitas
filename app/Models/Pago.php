<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    const PAGO = 'PAGO';
    const ABONADO = 'ABONADO';
    const ADELANTO = 'ADELANTO';
    const OBSERVADO = 'OBSERVADO';
    const PENDIENTE = 'PENDIENTE';
    const ABONADO_PARCIAL = 'ABONADO_PARCIAL';

    const PAGO_CODE = 1;
    const ABONADO_CODE = 2;
    const ADELANTO_CODE = 4;
    const OBSERVADO_CODE = 6;
    const PENDIENTE_CODE = 5;
    const ABONADO_PARCIAL_CODE = 3;

    public static $migrateCondiciones = [
        'PAGO' => 1,
        'ABONADO' => 2,
        'ABONADO_PARCIAL' => 3,
        'ADELANTO' => 4,
        'PENDIENTE' => 5,
        'OBSERVADO' => 6,
    ];

    protected $guarded = ['id'];


    public function setCondicionAttribute($value)
    {
        $this->setAttribute('condicion_code', self::$migrateCondiciones[$value] ?? $value);
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
