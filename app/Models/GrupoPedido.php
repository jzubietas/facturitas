<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoPedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded=['id'];

    public function pedidos(){
        return $this->belongsToMany(Pedido::class,'grupo_pedido_items')->withPivot([
            'razon_social',
            'codigo',
        ]);
    }

    public static function creteGroupByPedido(Pedido $pedido,$createAnother=false){
        $distrito = Distrito::query()->where('distrito', '=', $pedido->env_distrito)->first();
        $data = [
            "zona" => optional($distrito)->zona ?? $pedido->env_zona ?? 'n/a',
            "provincia" => optional($distrito)->provincia ?? $pedido->env_destino ?? 'n/a',//LIMA
            'distrito' => optional($distrito)->distrito ?? $pedido->env_distrito ?? 'n/a',//LOS OLIVOS
            'direccion' => $pedido->env_direccion ?: 'n/a',//olva
            'referencia' => $pedido->env_referencia ?: 'n/a',//olva
            'cliente_recibe' => $pedido->env_nombre_cliente_recibe ?? 'n/a',//olva
            'telefono' => $pedido->env_celular_cliente_recibe ?? 'n/a',//n/a
        ];
        if($createAnother){
            return GrupoPedido::create($data);
        }
        return GrupoPedido::updateOrCreate($data);
    }
}
