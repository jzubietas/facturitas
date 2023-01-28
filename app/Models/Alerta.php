<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeNoFinalize($query)
    {
        return $query->whereNull($this->qualifyColumn('finalized_at'));
    }

    public function scopeNoReadTwoHours($query)
    {
        return $query->where(function ($query) {
            $query->whereNull($this->qualifyColumn('read_at'))
                ->orWhere($this->qualifyColumn('read_at'), '<', now()->subHours(2));
        });
    }

    public function scopeWithCurrentUser($query)
    {
        return $query->where($this->qualifyColumn('user_id'), auth()->id());
    }
}
