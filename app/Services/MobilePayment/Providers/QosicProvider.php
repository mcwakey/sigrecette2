<?php

namespace App\Services\MobilePayment\Providers;

use App\Contracts\MobilePaymentProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class QosicProvider implements MobilePaymentProviderInterface
{
    public function initiate(array $data): array
    {
        $config = config('mobile-payment.providers.qosic');

        try {
            $response = Http::withBasicAuth($config['username'], $config['password'])
                ->timeout($config['timeout'])
                ->post($config['base_url'] . '/QosicBridge/user/requestpayment', [
                    'msisdn' => $data['phone_number'],
                    'amount' => $data['amount'],
                    'firstname' => 'SIG',
                    'lastname' => 'RECETTE',
                    'transref' => $data['reference'],
                ]);

            $body = $response->json();

            return [
                'success' => $response->successful() && ($body['responsecode'] ?? '') === '00',
                'external_id' => $body['serviceref'] ?? null,
                'message' => $body['responsemsg'] ?? null,
                'raw' => $body ?? [],
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('QOSIC initiate error', [
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
        $config = config('mobile-payment.providers.qosic');

        try {
            $response = Http::withBasicAuth($config['username'], $config['password'])
                ->timeout($config['timeout'])
                ->post($config['base_url'] . '/QosicBridge/user/gettransactionstatus', [
                    'transref' => $reference,
                ]);

            $body = $response->json();
            $code = $body['responsecode'] ?? '';

            $status = match (true) {
                $code === '00' => 'success',
                in_array($code, ['01', '11', '12']) => 'pending',
                default => 'failed',
            };

            return [
                'status' => $status,
                'external_id' => $body['serviceref'] ?? null,
                'amount' => $body['amount'] ?? null,
                'raw' => $body ?? [],
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('QOSIC verify error', [
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
        return 'qosic';
    }
}
