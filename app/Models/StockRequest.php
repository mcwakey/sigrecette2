<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'req_no',
        'req_id',
        'req_desc',
        'qty',
        'start_no',
        'end_no',
        'last_no',
        'taxable_id',
        'req_type',
        'user_id',
        'type',
    ];

    public function taxable()
    {
        return $this->belongsTo(Taxable::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
