<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
        'category_id',

    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //public function taxpayer(){return $this->belongsTo(Taxpayer::class);}
}
