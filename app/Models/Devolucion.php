<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Devolucion extends Model
{
    use HasFactory;
    use Notifiable;

    const RECHAZADO = '0';
    const PENDIENTE = '1';
    const DEVUELTO = '2';
    const ACEPTADO = '3';

    protected $guarded = ['id'];
    /*protected $fillable = [
        "pago_id",
        "client_id",
        "asesor_id",
        "amount",

        "bank_destino",
        "bank_number",
        "num_operacion",

        "status",
        "voucher_disk",
        "voucher_path",
        "returned_at",
    ];*/

    protected $casts = [
        'amount' => 'double',
        'returned_at' => 'datetime',
    ];

    protected $appends = [
        'amount_format',
        'code_id',
        'estado_text',
        'estado_color',
    ];

    public function getEstadoTextAttribute($value)
    {
        switch ($this->status) {
            case self::ACEPTADO:
                return 'ACEPTADO';
            case self::RECHAZADO:
                return 'RECHAZADO';
            case self::DEVUELTO:
                return 'DEVUELTO';
            default:
                return 'PENDIENTE';
        }
    }

    public function getEstadoColorAttribute($value)
    {
        switch ($this->status) {
            case self::ACEPTADO:
                return 'info';
            case self::RECHAZADO:
                return 'danger';
            case self::DEVUELTO:
                return 'success';
            default:
                return 'dark';
        }
    }

    public function getAmountFormatAttribute($value)
    {
        $a = new \NumberFormatter("es-PE", \NumberFormatter::CURRENCY);
        return $a->formatCurrency($this->getAttribute("amount"), "PEN");
    }

    public function getCodeIdAttribute($value)
    {
        return generate_correlativo("DEV", $this->id ?? 0);
    }

    public function scopeNoAtendidos($query)
    {
        $query->where('status', '=', self::PENDIENTE);
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'client_id');
    }

    public function asesor()
    {
        return $this->belongsTo(User::class);
    }
}
