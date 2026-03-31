<?php

namespace App\Enums;

enum StatisticKeysEnums: string
{
    case BY_GENDER = 'by_gender';
    case BY_CATEGORY = 'by_category';
    case BY_ACTIVITY = 'by_activity';
    case BY_CANTON = 'by_canton';
    case BY_TOWN = 'by_town';
    case BY_ZONE = 'by_zone';
    case BY_TAXABLE = 'by_taxable';
    case BY_STATE = 'by_state';
    case BY_INVOICE = 'by_invoice_count';
    case BY_INVOICE_COMPTANT = 'by_invoice_count_comptant';
    case BY_TAXLABEL = 'by_taxlabel';
}
