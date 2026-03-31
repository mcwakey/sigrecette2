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
            $payload = [
                    'auth_token' => $config['api_key'],
                    'identifier' => $data['reference'],
                    'phone_number' => $data['phone_number'],
                    'amount' => (int) $data['amount'],
                    'description' => $data['description'],
                    'network' => $data['network'],
                ];

            Log::channel('daily')->info('PayGate: sending initiate request', [
                'url' => $config['base_url'] . '/api/v1/pay',
                'payload' => array_merge($payload, ['auth_token' => '***']),
            ]);

            $response = Http::withHeaders([])
                ->timeout($config['timeout'])
                ->post($config['base_url'] . '/api/v1/pay', $payload);

            $body = $response->json();

            Log::channel('daily')->info('PayGate: initiate response', [
                'http_status' => $response->status(),
                'body' => $body,
            ]);
            $statusCode = $body['status'] ?? -1;

            return [
                'success' => $response->successful() && (int) $statusCode === 0,
                'external_id' => $body['tx_reference'] ?? null,
                'message' => $this->initiateStatusMessage($statusCode),
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
            $response = Http::withHeaders([])
                ->timeout($config['timeout'])
                ->post($config['base_url'] . '/api/v1/status', [
                    'auth_token' => $config['api_key'],
                    'tx_reference' => $reference,
                ]);

            $body = $response->json();

            Log::channel('daily')->info('PayGate: verify response', [
                'reference' => $reference,
                'http_status' => $response->status(),
                'body' => $body,
            ]);
            $statusCode = (int) ($body['status'] ?? -1);

            $status = match ($statusCode) {
                0 => 'success',
                2 => 'pending',
                4 => 'failed',  // expired
                6 => 'failed',  // cancelled
                default => 'pending',
            };

            return [
                'status' => $status,
                'external_id' => $body['tx_reference'] ?? null,
                'payment_reference' => $body['payment_reference'] ?? null,
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

    private function initiateStatusMessage(int|string $code): string
    {
        return match ((int) $code) {
            0 => 'Transaction enregistrée avec succès',
            2 => 'Jeton d\'authentification invalide',
            4 => 'Paramètres invalides',
            6 => 'Doublons détectés. Une transaction avec le même identifiant existe déjà.',
            default => 'Erreur inconnue (code: ' . $code . ')',
        };
    }
}
