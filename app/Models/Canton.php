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
    public function towns()
    {
        return $this->hasMany(Town::class);
    }
}
