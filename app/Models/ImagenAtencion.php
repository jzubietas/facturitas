<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenAtencion extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];

    protected $appends=[
        'link'
    ];

    public function getLinkAttribute()
    {
        if (empty($this->attributes['adjunto'])) {
            return null;
        }
        return \Storage::disk('pstorage')->url('adjuntos/' . basename($this->attributes['adjunto']));
    }
}
