<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    const RECUPERADO_RECIENTE ="RECUPERADO RECIENTE";
    const RECUPERADO_PERMANENTE ="RECUPERADO PERMANENTE";
    const ABANDONO_RECIENTE ="ABANDONO RECIENTE";
    const ABANDONO_PERMANENTE ="ABANDONO PERMANENTE";
    const RECURRENTE ="RECURRENTE";
    const NUEVO ="NUEVO";
    const RECUPERADO ="RECUPERADO";

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function rucs(){
        return $this->hasMany(Ruc::class,'cliente_id');
    }

    public function porcentajes(){
        return $this->hasMany(Porcentaje::class,'cliente_id');
    }

    public function pedidos(){
        //SELECT SUM(saldo) FROM detalle_pedidos WHERE pedido_id=4;
        return $this->hasMany(Pedido::class,'cliente_id');
    }

}
