<?php

namespace App\Services\MobilePayment\Providers;

use App\Contracts\MobilePaymentProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedapayProvider implements MobilePaymentProviderInterface
{
    public function initiate(array $data): array
    {
        $config = config('mobile-payment.providers.fedapay');

        try {
            $response = Http::withToken($config['secret_key'])
                ->timeout($config['timeout'])
                ->post($config['base_url'] . '/v1/transactions', [
                    'description' => $data['description'],
                    'amount' => (int) $data['amount'],
                    'currency' => ['iso' => 'XOF'],
                    'callback_url' => url('/api/mobile-payment/callback/fedapay'),
                    'customer' => [
                        'phone_number' => ['number' => $data['phone_number'], 'country' => 'BJ'],
                    ],
                    'custom_metadata' => [
                        'reference' => $data['reference'],
                    ],
                ]);

            $body = $response->json();
            $transaction = $body['v1/transaction'] ?? $body;

            return [
                'success' => $response->successful(),
                'external_id' => (string) ($transaction['id'] ?? ''),
                'message' => $transaction['description'] ?? null,
                'raw' => $body ?? [],
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('FedaPay initiate error', [
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
        $config = config('mobile-payment.providers.fedapay');

        try {
            $response = Http::withToken($config['secret_key'])
                ->timeout($config['timeout'])
                ->get($config['base_url'] . '/v1/transactions', [
                    'custom_metadata[reference]' => $reference,
                ]);

            $body = $response->json();
            $transactions = $body['v1/transactions'] ?? [];
            $transaction = $transactions[0] ?? null;

            if (!$transaction) {
                return [
                    'status' => 'pending',
                    'external_id' => null,
                    'amount' => null,
                    'raw' => $body ?? [],
                ];
            }

            $status = match ($transaction['status'] ?? '') {
                'approved', 'transferred' => 'success',
                'declined', 'refunded', 'canceled' => 'failed',
                default => 'pending',
            };

            return [
                'status' => $status,
                'external_id' => (string) ($transaction['id'] ?? ''),
                'amount' => $transaction['amount'] ?? null,
                'raw' => $body ?? [],
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('FedaPay verify error', [
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
        return 'fedapay';
    }
}
