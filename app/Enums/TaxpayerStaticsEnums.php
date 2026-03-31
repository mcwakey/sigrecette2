<?php

namespace App\Enums;

enum TaxpayerStaticsEnums: string
{
    case BY_GENDER = 'gender';
    case BY_CATEGORY = 'category';
    case BY_ACTIVITY = 'activity';
    case BY_CANTON = 'canton';
    case BY_TOWN = 'town';
    case BY_ZONE = 'zone';
    case BY_TAXABLE = 'taxable';
    case BY_LABEL = 'label';
}
