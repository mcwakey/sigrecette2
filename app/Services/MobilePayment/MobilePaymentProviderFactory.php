<?php

namespace App\Services\MobilePayment;

use App\Contracts\MobilePaymentProviderInterface;
use App\Helpers\Constants;
use App\Services\MobilePayment\Providers\FedapayProvider;
use App\Services\MobilePayment\Providers\PaygateProvider;
use App\Services\MobilePayment\Providers\QosicProvider;
use InvalidArgumentException;

class MobilePaymentProviderFactory
{
    public function make(string $provider): MobilePaymentProviderInterface
    {
        return match ($provider) {
            Constants::PROVIDER_QOSIC => new QosicProvider(),
            Constants::PROVIDER_FEDAPAY => new FedapayProvider(),
            Constants::PROVIDER_PAYGATE => new PaygateProvider(),
            default => throw new InvalidArgumentException("Unsupported mobile payment provider: {$provider}"),
        };
    }
}
