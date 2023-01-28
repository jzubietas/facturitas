<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = [
        'date_at',
        'read_at',
        'finalized_at',
    ];

    protected $casts = [
        'metadata' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeNoFinalize($query)
    {
        return $query->whereNull($this->qualifyColumn('finalized_at'));
    }

    public function scopeNoReadTwoHours($query)
    {
        return $this->scopeNoReadTime($query, now()->subHours(2));
    }

    public function scopeNoReadTime($query, CarbonInterface $date)
    {
        return $query->where(function ($query) use ($date) {
            $query->whereNull($this->qualifyColumn('read_at'))
                ->orWhere($this->qualifyColumn('read_at'), '<', $date);
        });
    }

    public function scopeWithCurrentUser($query)
    {
        return $query->where($this->qualifyColumn('user_id'), auth()->id());
    }
}
