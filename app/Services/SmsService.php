<?php

namespace App\Services;

use App\Models\SmsLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send(string $phoneNumber, string $message, ?Model $loggable = null): SmsLog
    {
        $log = SmsLog::create([
            'phone_number' => $phoneNumber,
            'message' => $message,
            'provider' => config('mobile-payment.sms.provider'),
            'status' => 'pending',
            'loggable_type' => $loggable ? get_class($loggable) : null,
            'loggable_id' => $loggable?->getKey(),
        ]);

        try {
            $this->sendViaProvider($phoneNumber, $message);
            $log->update(['status' => 'sent']);
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'meta' => ['error' => $e->getMessage()],
            ]);

            Log::channel('daily')->warning('SMS send failed', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);
        }

        return $log;
    }

    protected function sendViaProvider(string $phoneNumber, string $message): void
    {
        $apiKey = config('mobile-payment.sms.api_key');
        $senderId = config('mobile-payment.sms.sender_id');

        if (empty($apiKey)) {
            Log::channel('daily')->info('SMS not sent — no API key configured', [
                'phone' => $phoneNumber,
            ]);
            return;
        }

        // Generic SMS API call — adapt to your actual SMS provider
        Http::timeout(15)->post(config('mobile-payment.sms.provider'), [
            'api_key' => $apiKey,
            'sender_id' => $senderId,
            'to' => $phoneNumber,
            'message' => $message,
        ]);
    }
}
