<?php

namespace App\Services;

use App\Models\SmsLog;
use App\Services\Sms\SmsProviderFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function __construct(
        protected SmsProviderFactory $factory,
    ) {}

    public function send(string $phoneNumber, string $message, ?Model $loggable = null): SmsLog
    {
        $provider = $this->factory->make();

        $log = SmsLog::create([
            'phone_number' => $phoneNumber,
            'message' => $message,
            'provider' => $provider->getName(),
            'status' => 'pending',
            'loggable_type' => $loggable ? get_class($loggable) : null,
            'loggable_id' => $loggable?->getKey(),
        ]);

        try {
            $result = $provider->send($phoneNumber, $message);

            $log->update([
                'status' => $result['success'] ? 'sent' : 'failed',
                'meta' => $result['raw'] ?? [],
            ]);
        } catch (\Throwable $e) {
            $log->update([
                'status' => 'failed',
                'meta' => ['error' => $e->getMessage()],
            ]);

            Log::channel('daily')->warning('SMS send failed', [
                'phone' => $phoneNumber,
                'provider' => $provider->getName(),
                'error' => $e->getMessage(),
            ]);
        }

        return $log;
    }
}
