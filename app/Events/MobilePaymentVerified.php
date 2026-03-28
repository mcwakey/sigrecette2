<?php

namespace App\Events;

use App\Models\MobilePaymentTransaction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MobilePaymentVerified
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public MobilePaymentTransaction $transaction,
    ) {}
}
