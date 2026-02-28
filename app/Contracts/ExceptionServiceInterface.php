<?php

namespace App\Contracts;

interface ExceptionServiceInterface
{
    public function getMessage(\Throwable $e): string;
}
