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
        'updated_at'
    ];
    protected $casts=[
        'activado_pedido'=>'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
