<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canton extends Model
{
    use HasFactory;

    // Assuming towns() is the relationship method in Canton model
    public function towns()
    {
        return $this->hasMany(Town::class);
    }
}
