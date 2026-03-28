<?php

return [
    'export_taxpayer_feature' => env('FEATURE_TAXPAYER_EXPORT', false),
    'export_invoice_feature' => env('FEATURE_INVOICE_EXPORT', false),
    'export_recovery_feature' => env('FEATURE_RECOVERY_EXPORT', false),
    'backup_feature' => env('FEATURE_BACKUP', false),
    'taxpayer_event_feature' => env('FEATURE_TAXPAYER_EVENT', false),
    'export_taxpayer_taxable_feature' => env('FEATURE_TAXPAYER_TAXABLE_EXPORT', false),
    'export_taxable_feature' => env('FEATURE_TAXABLE_EXPORT', false),
    'mobile_payment_feature' => env('FEATURE_MOBILE_PAYMENT', false),
    'sms_notifications_feature' => env('FEATURE_SMS_NOTIFICATIONS', false),
];
