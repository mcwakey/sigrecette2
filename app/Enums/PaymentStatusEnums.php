<?php

namespace App\Enums;

class PaymentStatusEnums
{
    public const PENDING = 'PENDING';
    public const  ACCOUNTED = 'ACCOUNTED';
    public const  CANCELED = 'CANCELED';
    public const  DONE = 'DONE';
    public const VERIFYING = 'VERIFYING';
    public const FAILED = 'FAILED';
    public const EXPIRED = 'EXPIRED';
}
