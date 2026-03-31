<?php

namespace App\Enums;

enum InvoiceStatusEnums: string
{
    case DRAFT = 'DRAFT';
    case P_ACCEPTED = 'ACCEPTED WITHOUT ORDER NO';
    case ACCEPTED = 'ACCEPTED';
    case REJECTED_BY_OR = 'REJECTED_BY_OR';
    case PENDING = 'PENDING';
    case REJECTED = 'REJECTED';
    case APPROVED = 'APPROVED';
    case APPROVED_CANCELLATION = 'APPROVED-CANCELLATION-OR-REDUCTION';
    case CANCELED = 'APPROVED-CANCELED';
    case REDUCED = 'APPROVED-REDUCED';
}
