<?php

namespace App\Enums;

enum PaymentStatusEnums: string
{
    case PENDING = 'PENDING';
    case ACCOUNTED = 'ACCOUNTED';
    case CANCELED = 'CANCELED';
    case DONE = 'DONE';
    case VERIFYING = 'VERIFYING';
    case FAILED = 'FAILED';
    case EXPIRED = 'EXPIRED';
}
