<?php

namespace App\Contracts;

interface SmsProviderInterface
{
    /**
     * Send an SMS message.
     *
     * @return array{success: bool, message: ?string, raw: array}
     */
    public function send(string $phoneNumber, string $message): array;

    /**
     * Get the provider name.
     */
    public function getName(): string;
}
