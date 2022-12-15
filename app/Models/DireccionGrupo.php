<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DireccionGrupo extends Model
{
    use HasFactory;

    //condicion_envio
    const CE_EN_REPARTO ="EN REPARTO";//1
    const CE_EN_REPARTO_CODE =8;//1
    

    
    const CE_ENTREGADO ="ENTREGADO";//2
    const CE_ENTREGADO_CODE =10;//2

    const CE_BANCARIZACION ="BANCARIZACION";//2
    const CE_BANCARIZACION_CODE =4;//2






    //subcondicion_envio
    const SCE_REGISTRADO = 'REGISTRADO';
    const SCE_EN_CAMINO = 'EN CAMINO';
    const SCE_EN_TIENDA_AGENTE = 'EN TIENDA/AGENTE';
    const SCE_ENTREGADO = 'ENTREGADO';
    const SCE_NO_ENTREGADO = 'NO ENTREGADO';
    const NULL = 'NULL';

    protected $guarded = ['id'];
    
}
