<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoMotorizadoHistory extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    protected $appends=[
        'created_at_format',
        'sustento_foto_link',
    ];
    public function getSustentoFotoLinkAttribute($key)
    {
        return foto_url($this->getAttribute('sustento_foto'));
    }

    public function getCreatedAtFormatAttribute($key)
    {
        return optional($this->getAttribute('created_at'))->format('d-m-Y h:i A');
    }
}
