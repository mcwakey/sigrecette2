<?php

return [
    'remote_base_url' => env('SYNC_REMOTE_BASE_URL'),
    'remote_token' => env('SYNC_REMOTE_TOKEN'),
    'allowed_ips' => env('SYNC_ALLOWED_IPS'),
    'timeout' => (int) env('SYNC_TIMEOUT', 15),
    'retries' => (int) env('SYNC_RETRIES', 3),
    'chunk_size' => (int) env('SYNC_CHUNK_SIZE', 200),
    'per_page' => (int) env('SYNC_PER_PAGE', 200),
    'export_cron' => env('SYNC_EXPORT_CRON', '*/10 * * * *'),
    'import_cron' => env('SYNC_IMPORT_CRON', '*/10 * * * *'),
    'log_channel' => env('SYNC_LOG_CHANNEL', 'sync'),
];
