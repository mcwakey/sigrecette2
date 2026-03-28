<?php

namespace App\Contracts;

interface MobilePaymentProviderInterface
{
    /**
     * Initiate a payment request to the provider.
     *
     * @param array{reference: string, amount: float, phone_number: string, description: string} $data
     * @return array{success: bool, external_id: ?string, message: ?string, raw: array}
     */
    public function initiate(array $data): array;

    /**
     * Verify transaction status with the provider.
     *
     * @return array{status: string, external_id: ?string, amount: ?float, raw: array}
     */
    public function verify(string $reference): array;

    /**
     * Get the provider name.
     */
    public function getName(): string;
}
