<?php

namespace App\Enums;

enum PaymentTypeEnums: string
{
    case CASH = 'CASH';
    case CHEQUE = 'CHEQUE';
    case DIGI = 'DIGI';
}
