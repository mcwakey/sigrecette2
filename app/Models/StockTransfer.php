<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'payment_id',
        'stock_request_id'
    ];

    public function taxable()
    {
        return $this->belongsTo(Taxable::class);
    }
    public function stock_request()
    {
        return $this->belongsTo(StockRequest::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class, "to_user_id");
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
    public static function buildAndGetStockTransferWithQuery($id)
    {
        $builder= StockTransfer::join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')
            ->join('users', 'stock_transfers.to_user_id', '=', 'users.id')
            ->select('stock_transfers.trans_id',
                DB::raw('CASE WHEN trans_type = "RECU" THEN qty END AS rc_qty'),
                DB::raw('CASE WHEN trans_type = "VENDU" THEN qty END AS vv_qty'),
                DB::raw('CASE WHEN trans_type = "RENDU" THEN qty END AS rd_qty'),
                DB::raw('stock_transfers.id AS id'),
                DB::raw('stock_transfers.trans_no AS trans_no'),
                //DB::raw('MAX(stock_transfers.trans_desc) AS trans_desc'),
                DB::raw('stock_transfers.start_no AS start_no'),
                DB::raw('stock_transfers.end_no AS end_no'),
                DB::raw('stock_transfers.last_no AS last_no'),
                DB::raw('stock_transfers.trans_type AS trans_type'),
                DB::raw('stock_transfers.type AS type'),
                DB::raw('stock_transfers.to_user_id AS to_user_id'),
                DB::raw('stock_transfers.created_at AS created_at'),
                DB::raw('stock_transfers.taxable_id AS taxable_id'))
            ->where('stock_transfers.to_user_id', $id)
            ->where('stock_transfers.type', "ACTIVE")
            // ->groupBy('stock_transfers.trans_id')
            ->orderBy('trans_id', 'desc');
        return $builder->get();
    }

}
