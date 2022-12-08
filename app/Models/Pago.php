<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    const PAGO = 1;
    const ABONADO = 2;
    const ADELANTO = 4;
    const OBSERVADO = 6;
    const PENDIENTE = 5;
    const ABONADO_PARCIAL = 3;

    protected $guarded = ['id'];

    public function pedidos()
    {

    }

    public function isAbonado(){
        return $this->condicion==self::ABONADO;
    }
    public function isAdelanto(){
        return $this->condicion==self::ADELANTO;
    }
    public function isObservado(){
        return $this->condicion==self::OBSERVADO;
    }
    public function isPago(){
        return $this->condicion==self::PAGO;
    }
    public function isPendiente(){
        return $this->condicion==self::PENDIENTE;
    }
    public function isAbonadoParcial(){
        return $this->condicion==self::ABONADO_PARCIAL;
    }
}
