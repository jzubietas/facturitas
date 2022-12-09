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
    const ACEPTADO = '2';
    const DEVUELTO = '3';

    protected $guarded = ['id'];
    /*protected $fillable = [
        "pago_id",
        "client_id",
        "asesor_id",
        "amount",
        "status",
        "voucher_path",
    ];*/

    protected $casts=[
        'amount'=>'double'
    ];

    public function getAmountFormatAttribute($value)
    {
        $a = new \NumberFormatter("es-PE", \NumberFormatter::CURRENCY);
        return $a->formatCurrency($this->getAttribute("amount"), "PEN");
    }

    public function scopeNoAtendidos($query){
        $query->where('status','=',self::PENDIENTE);
    }
    public function pago(){
        return $this->belongsTo(Pago::class);
    }
    public function cliente(){
        return $this->belongsTo(Cliente::class,'client_id');
    }
    public function asesor(){
        return $this->belongsTo(User::class);
    }
}
