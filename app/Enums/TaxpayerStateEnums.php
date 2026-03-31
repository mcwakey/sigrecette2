<?php

namespace App\Enums;

enum TaxpayerStateEnums: string
{
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case APPROVED = 'APPROVED';
}
