<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidosAnulacion extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $fillable = [
        'id',
        'pedido_id',
        'user_id_asesor',
        'motivo_solicitud',
        'files_asesor_ids',
        'estado_aprueba_asesor',
        'user_id_encargado',
        'motivo_sol_encargado',
        'files_encargado_ids',
        'estado_aprueba_encargado',
        'user_id_administrador',
        'motivo_sol_admin',
        'filesadmin_ids',
        'estado_aprueba_administrador',
        'user_id_jefeop',
        'motivo_jefeop_admin',
        'files_jefeop_ids',
        'estado_aprueba_jefeop',
        'created_at',
        'updated_at',
        'total_anular',
        'tipo',
        'state_solicitud',
        'resposable_create_asesor',
        'resposable_aprob_encargado',
        'files_responsable_asesor',
    ];
}
