<?php

namespace App\Enums;

enum ExportTypeEnums: string
{
    case INVOICE = 'INVOICE';
    case PAYMENT = 'PAYMENT';
    case TAXPAYER = 'TAXPAYER';
    case TAXPAYER_TAXABLE = 'TaxPayerTaxable';
    case TAXABLE = 'Taxable';
}
