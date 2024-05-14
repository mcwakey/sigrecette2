<?php

namespace App\Enums;

class InvoiceStatusEnums
{
    public const DRAFT  ='DRAFT';
    public const PENDING = 'PENDING';
    public const APPROVED = 'APPROVED';
    public const REJECTED = 'REJECTED';
    public const REJECTED_BY_OR = 'REJECTED_BY_OR';
    public const CANCELED = 'APPROVED-CANCELED';
    public const REDUCED = 'APPROVED-REDUCED';
    public const APPROVED_CANCELLATION = 'APPROVED-CANCELLATION-OR-REDUCTION';
}
