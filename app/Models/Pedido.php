<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    /**************
     * CONSTANTES PEDIDO
     */
    const POR_ATENDER = 'POR ATENDER';//1
    const EN_PROCESO_ATENCION = 'EN PROCESO ATENCION';//2
    const ATENDIDO = 'ATENDIDO';//3
    const ANULADO = 'ANULADO';//4

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
    const POR_ATENDER_PEDIDO = 'POR_ATENDER_PEDIDO';
    const INCOMPLETO = 'INCOMPLETO';
    const PDF = 'PDF';
    const BANCARIZACION = 'BANCARIZACION';
    const JEFE_OP = 'JEFE_OP';
    const COURIER = 'COURIER';
    const SOBRE_ENVIAR = 'SOBRE_ENVIAR';
    const EN_REPARTO = 'EN_REPARTO';
    const SEG_PROVINCIA = 'SEG_PROVINCIA';
    const ENTREGADO = 'ENTREGADO';

    /**************
     * CONSTANTES CONDICION ENVIO NUMERICO
     */
    const POR_ATENDER_PEDIDO_INT = 1;
    const INCOMPLETO_INT = 2;
    const PDF_INT = 3;
    const BANCARIZACION_INT = 4;
    const JEFE_OP_INT = 5;
    const COURIER_INT= 6;
    const SOBRE_ENVIAR_INT = 7;
    const EN_REPARTO_INT = 8;
    const SEG_PROVINCIA_INT = 9;
    const ENTREGADO_INT = 1;

    /* relacion de conciones de envio y enteros */

    public static $estadosCondicion = [
        'ANULADO' => 4,
        'POR ATENDER' => 1,
        'EN PROCESO ATENCIÓN' => 2,
        'ATENDIDO' => 3,
    ];

    public static $estadosCondicionCode = [
        4=> 'ANULADO',
        1=> 'POR ATENDER',
        2=> 'EN PROCESO ATENCIÓN' ,
        3=> 'ATENDIDO' ,
    ];

    /******************
     * CONDICION DE ENVIO
     */

    public static $estadosCondicionEnvio = [
        'POR ATENDER' => 1,
        'INCOMPLETO' => 2,
        'PDF' => 3,
        'BANCARIZACION' => 4,
        'JEFE_OP' => 5,
        'COURIER' => 6,
        'SOBRE_ENVIAR' => 7,
        'EN_REPARTO' => 8,
        'SEG_PROVINCIA' => 9,
        'ENTREGADO' => 10,
    ];

    public static $estadosCondicionEnvioCode = [
        1 => 'POR ATENDER',
        2 => 'INCOMPLETO',
        3 => 'PDF',
        4 => 'BANCARIZACION',
        5 => 'JEFE_OP',
        6 => 'COURIER',
        7 => 'SOBRE_ENVIAR',
        8 => 'EN_REPARTO',
        9 => 'SEG_PROVINCIA',
        10 => 'ENTREGADO'
    ];



    protected $guarded = ['id'];

    /* public function user()
    {
        return $this->belongsTo('App\Models\User');
    } */

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function setCondicionAttributeCode($value)
    {
        $this->attributes['condicion_code'] = $value;
        $this->setAttribute('condicion', self::$estadosCondicionCode[$value] ?? $value);
    }
}
