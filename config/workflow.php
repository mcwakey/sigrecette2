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
            InvoiceStatusEnums::DRAFT,
            InvoiceStatusEnums::REJECTED_BY_OR,
            InvoiceStatusEnums::ACCEPTED,
            InvoiceStatusEnums::PENDING,
            InvoiceStatusEnums::REJECTED,
            InvoiceStatusEnums::APPROVED,
            InvoiceStatusEnums::APPROVED_CANCELLATION,
            InvoiceStatusEnums::CANCELED,
            InvoiceStatusEnums::REDUCED,
        ],
        'transitions' => [
            "submit_for_accepted"=> [
                'from' => [InvoiceStatusEnums::DRAFT],
                'to' => [InvoiceStatusEnums::ACCEPTED, InvoiceStatusEnums::REJECTED_BY_OR],
            ],
            "submit_for_pending"=> [
                'from' => [InvoiceStatusEnums::ACCEPTED],
                'to' => InvoiceStatusEnums::PENDING,
                'guard' => [InvoiceGuard::class, 'canSubmitForPending'],
            ],
            "submit_for_approved" => [
                'from' => [InvoiceStatusEnums::PENDING],
                'to' => [InvoiceStatusEnums::APPROVED,InvoiceStatusEnums::APPROVED_CANCELLATION, InvoiceStatusEnums::REJECTED],

            ],
            "submit_for_reduced"=> [
                'from' => [InvoiceStatusEnums::APPROVED,InvoiceStatusEnums::APPROVED_CANCELLATION],
                'to' => [InvoiceStatusEnums::CANCELED, InvoiceStatusEnums::REDUCED],
            ],
        ],
    ],
];
