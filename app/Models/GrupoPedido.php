<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoPedido extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class, 'grupo_pedido_items')->withPivot([
            'razon_social',
            'codigo',
        ]);
    }

    public static function createGroupByPedido(Pedido $pedido, $createAnother = false)
    {
        return self::createGroupByArray([
            "zona" =>$pedido->env_zona,
            "provincia" => $pedido->env_destino,
            'distrito' =>$pedido->env_distrito,
            'direccion' => $pedido->env_direccion,
            'referencia' => $pedido->env_referencia,
            'cliente_recibe' =>$pedido->env_nombre_cliente_recibe,
            'telefono' => $pedido->env_celular_cliente_recibe,
        ],$createAnother);
    }

    public static function createGroupByArray($array, $createAnother = false)
    {
        $distrito = Distrito::query()->where('distrito', '=', data_get($array, 'distrito'))->first();
        $data = [
            "zona" => optional($distrito)->zona ?? data_get($array, 'zona') ?? 'n/a',
            "provincia" => optional($distrito)->provincia ?? data_get($array, 'provincia') ?? 'n/a',//LIMA
            'distrito' => optional($distrito)->distrito ?? data_get($array, 'distrito') ?? 'n/a',//LOS OLIVOS
            'direccion' => data_get($array, 'direccion') ?: 'n/a',//olva
            'cliente_recibe' => data_get($array, 'cliente_recibe') ?? 'n/a',//olva
        ];
        if ($createAnother) {
            return GrupoPedido::create($data);
        }
        return GrupoPedido::updateOrCreate($data, [
            'referencia' => data_get($array, 'referencia') ?: 'n/a',//olva
            'telefono' => data_get($array, 'telefono') ?? 'n/a',//n/a
        ]);
    }
}
