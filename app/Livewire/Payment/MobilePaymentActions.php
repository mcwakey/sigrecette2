<?php

namespace App\Livewire\Payment;

use App\Models\MobilePaymentTransaction;
use App\Services\MobilePayment\MobilePaymentService;
use App\Jobs\VerifyMobilePaymentJob;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class MobilePaymentActions extends Component
{
    public $transactionId;
    public $status;
    public $message;

    public function retryVerification(int $id)
    {
        $transaction = MobilePaymentTransaction::find($id);
        if (!$transaction) {
            $this->dispatch('notify', type: 'error', message: 'Transaction introuvable.');
            return;
        }

        Log::channel('daily')->info('Mobile payment list: manual retry verification', [
            'transaction_id' => $transaction->id,
            'reference' => $transaction->reference,
            'current_status' => $transaction->status,
        ]);

        /** @var MobilePaymentService $service */
        $service = app(MobilePaymentService::class);
        $result = $service->verifyWithProvider($transaction);

        Log::channel('daily')->info('Mobile payment list: retry result', [
            'transaction_id' => $transaction->id,
            'result' => $result,
        ]);

        $message = match ($result) {
            'success' => 'Paiement confirmé avec succès!',
            'failed' => 'Le paiement a échoué.',
            default => 'Vérification relancée. Statut: en attente.',
        };

        if ($result === 'pending') {
            VerifyMobilePaymentJob::dispatch($transaction->fresh());
        }

        $type = $result === 'success' ? 'success' : ($result === 'failed' ? 'error' : 'info');

        $this->dispatch('notify', type: $type, message: $message);
        $this->dispatch('refreshTable');
    }

    public function render()
    {
        return view('livewire.payment.mobile-payment-actions');
    }
}
