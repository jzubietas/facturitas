<?php

namespace App\Models;

use App\Traits\CommonModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    use CommonModel;

    protected $guarded = ['id'];

    protected $fillable = [
        'email',
        'token',
        'created_at',
        'updated_at'
    ];
}
