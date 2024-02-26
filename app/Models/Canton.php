<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canton extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',

    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cantons';
    
    // Assuming towns() is the relationship method in Canton model
    public function towns()
    {
        return $this->hasMany(Town::class);
    }

    // public function ereas()
    // {
    //     return $this->hasMany(Erea::class,"town_id");
    // }
}
