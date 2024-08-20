<?php
return [
    'export_taxpayer_feature' => env('FEATURE_TAXPAYER_EXPORT', false),
    'export_invoice_feature' => env('FEATURE_INVOICE_EXPORT', false),
    'export_recovery_feature' => env('FEATURE_RECOVERY_EXPORT', false),
    'backup_feature' => env('FEATURE_BACKUP', false),
    'taxpayer_event_feature' => env('FEATURE_TAXPAYER_EVENT', false),

];
