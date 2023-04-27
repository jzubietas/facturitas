<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];
  const FISICO_SIN_BANCA_COD = 1;
  const FISICO_SIN_BANCA_DESC = 'FISICO - sin banca';

  const FISICO_BANCA_COD = 2;
  const FISICO_BANCA_DESC = 'FISICO - banca';

  const ELECTRONICA_SIN_BANCA_COD = 3;
  const ELECTRONICA_SIN_BANCA_DESC = 'ELECTRONICA - sin banca';
  const ELECTRONICA_BANCA_COD = 4;
  const ELECTRONICA_BANCA_DESC = 'ELECTRONICA - banca';

  protected $fillable = [
    'id',
    'pedido_id',
    'codigo',
    'nombre_empresa',
    'mes',
    'anio',
    'ruc',
    'cantidad',
    'adjunto',
    'tipo_banca',
    'porcentaje',
    'ft',
    'courier',
    'total',
    'saldo',
    'descripcion',
    'nota',
    'envio_doc',
    'fecha_envio_doc',
    'cant_compro',
    'fecha_envio_doc_fis',
    'foto1',
    'foto2',
    'atendido_por',
    'fecha_recepcion',
    'estado',
    'created_at',
    'updated_at',
    'atendido_por_id',
    'sobre_valida',
      'user_reg'
  ];

}
