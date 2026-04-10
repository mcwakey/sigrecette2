<?php

namespace App\Services\Sms\Providers;

use App\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsvasProvider implements SmsProviderInterface
{
    public function send(string $phoneNumber, string $message): array
    {
        $config = config('mobile-payment.sms.providers.smsvas');

        if (empty($config['token'])) {
            Log::channel('daily')->info('Smsvas: no token configured, SMS not sent');
            return ['success' => false, 'message' => 'No token configured', 'raw' => []];
        }

        try {
            $response = Http::timeout($config['timeout'] ?? 15)
                ->get($config['base_url'] . '/api/sms', [
                    'token' => $config['token'],
                    'to' => $phoneNumber,
                    'text' => $message,
                    'from' => $config['from'] ?: config('mobile-payment.sms.sender_id', 'SIGRECETTE'),
                ]);

            $body = $response->json() ?? [];
            $status = $body['Status'] ?? null;
            $success = in_array($status, [111, 112]);

            Log::channel('daily')->info('Smsvas: response', [
                'phone' => $phoneNumber,
                'status' => $status,
                'body' => $body,
            ]);

            return [
                'success' => $success,
                'message' => $body['Description'] ?? null,
                'raw' => $body,
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Smsvas: send error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => $e->getMessage(), 'raw' => []];
        }
    }

    public function getName(): string
    {
        return 'smsvas';
    }
}
