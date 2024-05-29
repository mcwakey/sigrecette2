<?php

namespace App\Enums;

class InvoiceStatusEnums
{
    public const DRAFT  ='DRAFT';//after draft invoice can be go to Pending or REJECTED_BY_OR
    public const P_ACCEPTED ="ACCEPTED WITHOUT ORDER NO";
    public const ACCEPTED ="ACCEPTED";
    public const REJECTED_BY_OR = 'REJECTED_BY_OR';
    public const PENDING = 'PENDING';// after pending invoices can be REJECTED or  APPROVED or APPROVED_CANCELLATION
    public const REJECTED = 'REJECTED';
    public const APPROVED = 'APPROVED';//after Apporved invoices can be canceled or reduced
    public const APPROVED_CANCELLATION = 'APPROVED-CANCELLATION-OR-REDUCTION';// APPROVED_CANCELLATION is just type of apporved to now if invoices is canceled or reuced before
    public const CANCELED = 'APPROVED-CANCELED';
    public const REDUCED = 'APPROVED-REDUCED';
}
