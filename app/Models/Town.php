<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'canton_id',

    ];
    
    // Assuming canton() is the inverse relationship method in Town model
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

    public function taxpayers()
    {
        return $this->hasMany(Taxpayer::class);
    }

    public function ereas()
    {
        return $this->hasMany(Erea::class);
    }
}
