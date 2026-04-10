<?php

namespace App\Services\Sms;

use App\Contracts\SmsProviderInterface;
use App\Services\Sms\Providers\BestcomProvider;
use App\Services\Sms\Providers\NaloProvider;
use App\Services\Sms\Providers\SmsvasProvider;
use InvalidArgumentException;

class SmsProviderFactory
{
    public function make(?string $provider = null): SmsProviderInterface
    {
        $provider = $provider ?: config('mobile-payment.sms.default_provider', 'smsvas');

        return match ($provider) {
            'smsvas' => new SmsvasProvider(),
            'bestcom' => new BestcomProvider(),
            'nalo' => new NaloProvider(),
            default => throw new InvalidArgumentException("Unsupported SMS provider: {$provider}"),
        };
    }
}
