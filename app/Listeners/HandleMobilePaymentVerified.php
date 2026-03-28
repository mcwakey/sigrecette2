<?php

namespace App\Listeners;

use App\Events\MobilePaymentVerified;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class HandleMobilePaymentVerified
{
    public function __construct(
        protected SmsService $smsService,
    ) {}

    public function handle(MobilePaymentVerified $event): void
    {
        $transaction = $event->transaction;

        if (!config('mobile-payment.sms.enabled')) {
            return;
        }

        try {
            $message = "Votre paiement de {$transaction->amount} FCFA pour l'avis {$transaction->invoice->invoice_no} a été confirmé. Ref: {$transaction->reference}";

            $this->smsService->send(
                $transaction->phone_number,
                $message,
                $transaction,
            );
        } catch (\Throwable $e) {
            Log::channel('daily')->warning('SMS notification failed for mobile payment', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
