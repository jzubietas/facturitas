<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteDuplicado extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];
    protected $dates=[
        'temporal_update',
        'created_at',
        'updated_at',
        'fecha_anulacion',
        'fecha_ultimopedido',
        'fecha_llamado',
        'fecha_chateado'
    ];
    protected $casts=[
        'activado_pedido'=>'integer'
    ];

    protected $fillable = [
        'id',
        'cliente_id',
        'correlativo',
        'user_id',
        'user_identificador',
        'user_clavepedido',
        'nombre',
        'icelular',
        'celular',
        'tipo',
        'provincia',
        'distrito',
        'direccion',
        'referencia',
        'dni',
        'saldo',
        'deuda',
        'pidio',
        'estado',
        'created_at',
        'updated_at',
        'crea_temporal',
        'activado_tiempo',
        'activado_pedido',
        'temporal_update',
        'situacion',
        'congelado',
        'sust_congelado',
        'sust_otro_congelado',
        'user_congelacion_id',
        'responsable_congelacion',
        'bloqueado',
        'sust_bloqueado',
        'sust_otro_bloqueado',
        'user_bloqueado_id',
        'responsable_bloqueo',
        'motivo_anulacion',
        'responsable_anulacion',
        'user_anulacion_id',
        'fecha_anulacion',
        'path_adjunto_anular',
        'path_adjunto_anular_disk',
        'agenda',
        'fecha_ultimopedido',
        'codigo_ultimopedido',
        'pago_ultimopedido',
        'pagado_ultimopedido',
        'fsb_porcentaje',
        'fcb_porcentaje',
        'esb_porcentaje',
        'ecb_porcentaje',
        'llamado',
        'asesor_llamado',
        'user_llamado',
        'fecha_llamado',
        'chateado',
        'asesor_chateado',
        'user_chateado',
        'fecha_chateado',
        'total_llamadas',
        'total_chats',
        'grupo_publicidad',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
