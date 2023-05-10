<?php

namespace App\Models;

use App\Traits\CommonModel;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttachCorrection extends Model
{
    use HasFactory;
    use CommonModel;

    //protected $guarded = ['id'];

    protected $fillable = [
        'correction_id',
        'type',
        'name',
        'file_name',
        'mime_type',
        'disk',
        'estado'
    ];

}
