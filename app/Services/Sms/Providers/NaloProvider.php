<?php

namespace App\Services\Sms\Providers;

use App\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NaloProvider implements SmsProviderInterface
{
    public function send(string $phoneNumber, string $message): array
    {
        $config = config('mobile-payment.sms.providers.nalo');

        if (empty($config['api_key'])) {
            Log::channel('daily')->info('Nalo: no API key configured, SMS not sent');
            return ['success' => false, 'message' => 'No API key configured', 'raw' => []];
        }

        try {
            $response = Http::timeout($config['timeout'] ?? 15)
                ->post($config['base_url'] . '/smsbackend/Aboroye_Standard/compose_sms.php', [
                    'key' => $config['api_key'],
                    'msisdn' => $phoneNumber,
                    'message' => $message,
                    'sender_id' => $config['sender_id'] ?: config('mobile-payment.sms.sender_id', 'SIGRECETTE'),
                    'type' => $config['type'] ?? 0,
                ]);

            $body = $response->json() ?? [];
            $status = $body['status'] ?? null;
            $success = $status === 'success' || $status === '1801';

            Log::channel('daily')->info('Nalo: response', [
                'phone' => $phoneNumber,
                'status' => $status,
                'body' => $body,
            ]);

            return [
                'success' => $success,
                'message' => $body['message'] ?? null,
                'raw' => $body,
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Nalo: send error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => $e->getMessage(), 'raw' => []];
        }
    }

    public function getName(): string
    {
        return 'nalo';
    }
}
