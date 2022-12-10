<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    //condicion del pedido
    const POR_ATENDER = 'POR ATENDER';//1
    const EN_PROCESO_ATENCION = 'EN PROCESO ATENCION';//2
    const ATENDIDO = 'ATENDIDO';//3
    const ANULADO = 'ANULADO';//4

    //envio
    const ENVIO_CONFIRMAR_RECEPCION = '1';//ENVIADO CONFIRMAR RECEPCION
    const ENVIO_RECIBIDO = '2';//ENVIADO RECIBIDO

    //condicion de envio
    const PENDIENTE_DE_ENVIO = 'PENDIENTE DE ENVIO';
    const EN_REPARTO = 'EN REPARTO';
    const ENTREGADO = 'ENTREGADO';
    
    protected $guarded = ['id'];

    /* public function user()
    {
        return $this->belongsTo('App\Models\User');
    } */
}
