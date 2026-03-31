<?php

namespace App\Services\MobilePayment\Providers;

use App\Contracts\MobilePaymentProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaygateProvider implements MobilePaymentProviderInterface
{
    public function initiate(array $data): array
    {
        $config = config('mobile-payment.providers.paygate');

        try {
            $response = Http::withHeaders([
                // 'Authorization' => 'Bearer ' . $config['api_key'],
            ])
                ->timeout($config['timeout'])
                ->post($config['base_url'] . '/api/v1/pay', [
                    'auth_token' => $config['api_key'],
                    'identifier' => $data['reference'],
                    'phone_number' => $data['phone_number'],
                    'amount' => (int) $data['amount'],
                    'description' => $data['description'],
                    'network' => $data['network'],
                ]);

            $body = $response->json();

            return [
                'success' => $response->successful() && ($body['status'] ?? '') === 'success',
                'external_id' => $body['tx_reference'] ?? null,
                'message' => $body['message'] ?? null,
                'raw' => $body ?? [],
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('PayGate initiate error', [
                'message' => $e->getMessage(),
                'reference' => $data['reference'],
            ]);

            return [
                'success' => false,
                'external_id' => null,
                'message' => $e->getMessage(),
                'raw' => [],
            ];
        }
    }

    public function verify(string $reference): array
    {
        $config = config('mobile-payment.providers.paygate');

        try {
            $response = Http::withHeaders([
                // 'Authorization' => 'Bearer ' . $config['api_key'],
            ])
                ->timeout($config['timeout'])
                ->post($config['base_url'] . '/api/v1/status', [
                    'auth_token' => $config['api_key'],
                    'tx_reference' => $reference,
                ]);

            $body = $response->json();
            $txStatus = $body['payment_status'] ?? $body['status'] ?? '';

            $status = match ($txStatus) {
                'completed', 'success' => 'success',
                'failed', 'error', 'cancelled' => 'failed',
                default => 'pending',
            };

            return [
                'status' => $status,
                'external_id' => $body['tx_reference'] ?? null,
                'amount' => $body['amount'] ?? null,
                'raw' => $body ?? [],
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('PayGate verify error', [
                'message' => $e->getMessage(),
                'reference' => $reference,
            ]);

            return [
                'status' => 'pending',
                'external_id' => null,
                'amount' => null,
                'raw' => ['error' => $e->getMessage()],
            ];
        }
    }

    public function getName(): string
    {
        return 'paygate';
    }
}
