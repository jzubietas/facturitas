<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenAtencion extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends=[
        'link'
    ];
    public function scopeActivo($query, $status = '1')
    {
        return $query->where($this->qualifyColumn('estado'), '=', $status);
    }

    public function getLinkAttribute()
    {
        if (empty($this->attributes['adjunto'])) {
            return null;
        }
        return \Storage::disk('pstorage')->url('adjuntos/' . basename($this->attributes['adjunto']));
    }
}
