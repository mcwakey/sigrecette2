<?php

namespace App\Enums;

enum InvoicePayStatusEnums: string
{
    case OWING = 'OWING';
    case PART_PAID = 'PART PAID';
    case PAID = 'PAID';
}
