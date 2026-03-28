<?php

namespace App\Jobs;

use App\Models\MobilePaymentTransaction;
use App\Services\MobilePayment\MobilePaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class VerifyMobilePaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public function __construct(
        public MobilePaymentTransaction $transaction,
    ) {}

    public function backoff(): array
    {
        return config('mobile-payment.verification.backoff_seconds', [10, 30, 60, 120, 300]);
    }

    public function handle(MobilePaymentService $service): void
    {
        $this->transaction->refresh();

        if (!in_array($this->transaction->status, ['pending', 'verifying'])) {
            return;
        }

        if ($this->transaction->expires_at && $this->transaction->expires_at->isPast()) {
            $this->transaction->update(['status' => 'expired']);
            return;
        }

        $result = $service->verifyWithProvider($this->transaction);

        if ($result === 'pending' && $this->attempts() < $this->tries) {
            $this->release($this->backoff()[$this->attempts() - 1] ?? 300);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::channel('daily')->error('VerifyMobilePaymentJob failed permanently', [
            'transaction_id' => $this->transaction->id,
            'reference' => $this->transaction->reference,
            'error' => $exception->getMessage(),
        ]);

        $this->transaction->update(['status' => 'failed']);
    }
}
