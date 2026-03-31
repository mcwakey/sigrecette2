<?php

use App\Enums\InvoiceStatusEnums;
use App\Guards\InvoiceGuard;

return [
    'invoice' => [
        'type' =>  'state_machine',
        'marking_store' => [
            'property' => 'status',
        ],
        'supports' => ['App\Models\Invoice'],
        'places' => [
            InvoiceStatusEnums::DRAFT->value,
            InvoiceStatusEnums::REJECTED_BY_OR->value,
            InvoiceStatusEnums::ACCEPTED->value,
            InvoiceStatusEnums::PENDING->value,
            InvoiceStatusEnums::REJECTED->value,
            InvoiceStatusEnums::APPROVED->value,
            InvoiceStatusEnums::APPROVED_CANCELLATION->value,
            InvoiceStatusEnums::CANCELED->value,
            InvoiceStatusEnums::REDUCED->value,
        ],
        'transitions' => [
            "submit_for_accepted" => [
                'from' => [InvoiceStatusEnums::DRAFT->value],
                'to' => [InvoiceStatusEnums::ACCEPTED->value],
            ],
            "submit_for_reject_by_ord" => [
                'from' => [InvoiceStatusEnums::DRAFT->value],
                'to' => [InvoiceStatusEnums::REJECTED_BY_OR->value],
            ],
            "submit_for_pending" => [
                'from' => [InvoiceStatusEnums::ACCEPTED->value],
                'to' => InvoiceStatusEnums::PENDING->value,
                'guard' => [InvoiceGuard::class, 'canSubmitForPending'],
            ],
            "submit_for_approved" => [
                'from' => [InvoiceStatusEnums::PENDING->value],
                'to' => [InvoiceStatusEnums::APPROVED->value],
            ],
            "submit_for_approved_cancellation" => [
                'from' => [InvoiceStatusEnums::PENDING->value],
                'to' => [InvoiceStatusEnums::APPROVED_CANCELLATION->value],
            ],
            "submit_for_rejected" => [
                'from' => [InvoiceStatusEnums::PENDING->value],
                'to' => [InvoiceStatusEnums::REJECTED->value],
            ],
            "submit_for_reduced" => [
                'from' => [InvoiceStatusEnums::APPROVED->value, InvoiceStatusEnums::APPROVED_CANCELLATION->value],
                'to' => [InvoiceStatusEnums::REDUCED->value],
            ],
            "submit_for_canceled" => [
                'from' => [InvoiceStatusEnums::APPROVED->value, InvoiceStatusEnums::APPROVED_CANCELLATION->value],
                'to' => [InvoiceStatusEnums::CANCELED->value],
            ],
        ],
    ],
];
