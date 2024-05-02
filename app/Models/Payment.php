<?php

namespace App\Models;

use App\Enums\PaymentStatusEnums;
use App\Enums\PaymentTypeEnums;
use App\Helpers\Constants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Collection;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payment_type',
        'invoice_type',
        'reference',
        'description',
        'remaining_amount',
        'taxpayer_id',
        'invoice_id',
        'user_id',
        'r_user_id',
        'status',
        'uuid',
        'code',
        'deposit'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function taxpayer()
    {
        return $this->belongsTo(Taxpayer::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function r_user()
    {
        return $this->belongsTo(User::class);
    }
    public function stock_transfers()
    {
        return $this->hasMany(StockTransfer::class);
    }
    public static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            $payment->uuid = Uuid::uuid4()->toString();
        });
    }
    public static function getSumPaymentByCode($code, Invoice $invoice): int
    {
        $sum_payment = 0;
        foreach ($invoice->payments()->get() as $payment) {
            if ($payment->code == $code) {
                $sum_payment += $payment->amount;
            }
        }
        return $sum_payment;
    }
    public static function getPrintData():Collection{
        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");

        return Payment::whereNot('status', PaymentStatusEnums::PENDING)
            ->whereNotIn('payments.reference', [Constants::ANNULATION, Constants::REDUCTION])// Filter collector_deposits by taxpayer_id
        ->orderBy('created_at', 'asc')
            ->whereBetween('payments.created_at', [$startOfYear, $endOfYear])
            ->newQuery()
            ->get();
    }

}
