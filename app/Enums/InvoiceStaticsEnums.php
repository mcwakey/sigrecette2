<?php

namespace App\Enums;

enum InvoiceStaticsEnums: string
{
    case BY_INVOICE = 'invoice';
    case BY_INVOICE_COMPTANT = 'invoice_comptant';
    case BY_COUNT = 'invoice_count';
}
