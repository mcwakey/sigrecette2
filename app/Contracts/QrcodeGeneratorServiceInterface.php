<?php

namespace App\Contracts;

interface QrcodeGeneratorServiceInterface
{
    public function generate(?string $data, ?string $backgroundImagePath = null): mixed;
}
