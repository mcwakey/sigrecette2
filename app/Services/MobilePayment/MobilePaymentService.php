<?php

namespace App\Services\MobilePayment;

use App\Enums\PaymentStatusEnums;
use App\Events\MobilePaymentVerified;
use App\Models\Invoice;
use App\Models\MobilePaymentTransaction;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class MobilePaymentService
{
    public function __construct(
        protected MobilePaymentProviderFactory $factory,
    ) {}

    /**
     * Initiate a mobile payment transaction.
     */
    public function initiate(array $data): MobilePaymentTransaction
    {
        $provider = $this->factory->make($data['provider']);
        $reference = Uuid::uuid4()->toString();
        $expiryMinutes = config('mobile-payment.transaction_expiry_minutes', 30);

        $transaction = MobilePaymentTransaction::create([
            'reference' => $reference,
            'invoice_id' => $data['invoice_id'],
            'taxpayer_id' => $data['taxpayer_id'] ?: null,
            'amount' => $data['amount'],
            'phone_number' => $data['phone_number'],
            'provider' => $data['provider'],
            'status' => 'pending',
            'expires_at' => now()->addMinutes($expiryMinutes),
            'meta' => $data['meta'] ?? null,
            'user_id' => Auth::id(),
        ]);

        $invoice = Invoice::find($data['invoice_id']);
        $description = "Avis " . ($invoice->invoice_no ?? '');

        $result = $provider->initiate([
            'reference' => $reference,
            'amount' => $data['amount'],
            'phone_number' => $data['phone_number'],
            'description' => $description,
            'network' => $data['network'] ?? null,
        ]);

        $transaction->update([
            'external_id' => $result['external_id'] ?? null,
            'status' => $result['success'] ? 'verifying' : 'failed',
            'provider_response' => $result['raw'] ?? null,
        ]);

        if (!$result['success']) {
            Log::channel('daily')->warning('Mobile payment initiation failed', [
                'reference' => $reference,
                'provider' => $data['provider'],
                'message' => $result['message'] ?? 'Unknown error',
            ]);
        }

        return $transaction->fresh();
    }

    /**
     * Verify a transaction with the provider.
     */
    public function verifyWithProvider(MobilePaymentTransaction $transaction): string
    {
        $provider = $this->factory->make($transaction->provider);

        $result = $provider->verify($transaction->reference);

        $transaction->update([
            'verification_attempts' => $transaction->verification_attempts + 1,
            'last_checked_at' => now(),
            'provider_response' => $result['raw'] ?? $transaction->provider_response,
        ]);

        if ($result['status'] === 'success') {
            $this->handleVerificationSuccess($transaction);
            return 'success';
        }

        if ($result['status'] === 'failed') {
            $this->handleVerificationFailure($transaction);
            return 'failed';
        }

        return 'pending';
    }

    /**
     * Handle successful provider verification — create Payment records.
     */
    public function handleVerificationSuccess(MobilePaymentTransaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $transaction->update([
                'status' => 'success',
                'verified_at' => now(),
            ]);

            $invoice = Invoice::find($transaction->invoice_id);
            if (!$invoice) {
                Log::channel('daily')->error('Mobile payment verified but invoice not found', [
                    'transaction_id' => $transaction->id,
                    'invoice_id' => $transaction->invoice_id,
                ]);
                return;
            }

            $meta = $transaction->meta ?? [];
            $code = $meta['code'] ?? null;

            $paymentData = [
                'invoice_id' => $invoice->invoice_no,
                'taxpayer_id' => $transaction->taxpayer_id,
                'amount' => $transaction->amount,
                'payment_type' => 'DIGI',
                'reference' => $transaction->reference,
                'code' => $code,
                'description' => "Avis " . $invoice->invoice_no,
                'remaining_amount' => 0,
                'user_id' => $transaction->user_id,
                'invoice_type' => $invoice->type,
                'status' => PaymentStatusEnums::ACCOUNTED,
                'provider' => $transaction->provider,
                'phone_number' => $transaction->phone_number,
                'external_id' => $transaction->external_id,
                'notes' => 'Paiement mobile vérifié automatiquement',
            ];

            $paid = Payment::getPaid($invoice->invoice_no);
            $payments = Invoice::getCode($invoice->id, $transaction->amount, $paymentData);

            if ($payments) {
                foreach ($payments as $paymentSplit) {
                    Payment::create($paymentSplit);
                }
            }

            $newPaid = Payment::getPaid($invoice->invoice_no);
            $payStatus = $newPaid >= $invoice->amount ? 'PAID' : 'PART PAID';
            $invoice->update(['pay_status' => $payStatus]);

            event(new MobilePaymentVerified($transaction));
        });
    }

    /**
     * Handle failed verification.
     */
    public function handleVerificationFailure(MobilePaymentTransaction $transaction): void
    {
        $transaction->update(['status' => 'failed']);

        Log::channel('daily')->info('Mobile payment verification failed', [
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
            'provider' => $transaction->provider,
        ]);
    }

    /**
     * Mark expired transactions.
     */
    public function markExpired(): int
    {
        return MobilePaymentTransaction::whereIn('status', ['pending', 'verifying'])
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);
    }
}
