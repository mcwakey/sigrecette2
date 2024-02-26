<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Erea extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'town_id'
    ];
    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function taxpayers()
    {
        return $this->hasMany(Taxpayer::class);
    }
}
