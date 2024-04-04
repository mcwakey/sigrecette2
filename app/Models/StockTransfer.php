<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'trans_no',
        'trans_id',
        'type',
        'qty',
        'start_no',
        'end_no',
        'code',
        'last_no',
        'taxable_id',
        'trans_type',
        'by_user_id',
        'to_user_id',
    ];
    
    public function taxable()
    {
        return $this->belongsTo(Taxable::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, "to_user_id");
    }
}
