<?php

namespace App\Services\Sms\Providers;

use App\Contracts\SmsProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BestcomProvider implements SmsProviderInterface
{
    public function send(string $phoneNumber, string $message): array
    {
        $config = config('mobile-payment.sms.providers.bestcom');

        if (empty($config['api_key']) || empty($config['api_secret'])) {
            Log::channel('daily')->info('Bestcom: no API keys configured, SMS not sent');
            return ['success' => false, 'message' => 'No API keys configured', 'raw' => []];
        }

        try {
            $response = Http::timeout($config['timeout'] ?? 15)
                ->asForm()
                ->post($config['base_url'] . '/api/send-sms', [
                    'Api_Key' => $config['api_key'],
                    'Api_secret' => $config['api_secret'],
                    'Contact' => $phoneNumber,
                    'Titre' => $config['titre'] ?: config('mobile-payment.sms.sender_id', 'SIGRECETTE'),
                    'Message' => $message,
                ]);

            $body = $response->json() ?? [];
            $info = $body['info'] ?? [];
            $success = ($info['status'] ?? null) == 1;

            Log::channel('daily')->info('Bestcom: response', [
                'phone' => $phoneNumber,
                'status' => $info['status'] ?? null,
                'body' => $body,
            ]);

            return [
                'success' => $success,
                'message' => $info['value'] ?? null,
                'raw' => $body,
            ];
        } catch (\Throwable $e) {
            Log::channel('daily')->error('Bestcom: send error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'message' => $e->getMessage(), 'raw' => []];
        }
    }

    public function getName(): string
    {
        return 'bestcom';
    }
}
