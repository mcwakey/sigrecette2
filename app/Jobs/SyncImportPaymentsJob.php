<?php
namespace App\Jobs;

use App\Models\SyncRun;
use App\Services\Sync\PaymentImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncImportPaymentsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 120;
    public $tries = 3;

    public function handle(): void
    {
        $baseUrl = config('sync.remote_base_url');
        $token = config('sync.remote_token');
        $perPage = config('sync.per_page', 200);
        $timeout = config('sync.timeout', 15);
        $retries = config('sync.retries', 3);
        $logChannel = config('sync.log_channel', 'sync');

        if (!$baseUrl || !$token) {
            Log::channel($logChannel)->warning('sync import payments skipped: missing remote config');
            return;
        }

        $lastSuccess = SyncRun::where('direction', 'import')
            ->where('entity', 'payments')
            ->where('status', 'success')
            ->orderByDesc('finished_at')
            ->first();

        $cursor = $lastSuccess?->finished_at?->toISOString();

        $run = SyncRun::create([
            'direction' => 'import',
            'entity' => 'payments',
            'status' => 'started',
            'started_at' => now(),
            'cursor' => $cursor,
        ]);

        $service = new PaymentImportService();
        $page = 1;
        $processed = 0;
        $succeeded = 0;
        $failed = 0;
        $errorSample = [];
        $lastSyncedAt = null;

        try {
            do {
                $response = Http::timeout($timeout)
                    ->retry($retries, 500)
                    ->withToken($token)
                    ->get(rtrim($baseUrl, '/').'/v1/sync/payments', [
                        'updated_since' => $cursor,
                        'page' => $page,
                        'per_page' => $perPage,
                    ]);

                if ($response->failed()) {
                    $failed++;
                    $errorSample[] = [
                        'reason' => 'remote_error',
                        'status' => $response->status(),
                    ];
                    Log::channel($logChannel)->error('sync import payments failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    break;
                }

                $payload = $response->json();
                $payments = $payload['data'] ?? [];
                $meta = $payload['meta'] ?? [];

                if (!empty($payments)) {
                    $result = $service->import($payments);
                    $processed += count($payments);
                    $succeeded += $result['created'] + $result['updated'];
                    $failed += $result['skipped'];
                    if (!empty($result['errors'])) {
                        $errorSample = array_slice(array_merge($errorSample, $result['errors']), 0, 50);
                    }
                    Log::channel($logChannel)->info('sync import payments batch', [
                        'page' => $page,
                        'processed' => count($payments),
                        'created' => $result['created'],
                        'updated' => $result['updated'],
                        'skipped' => $result['skipped'],
                    ]);
                }

                $page++;

                if (!empty($meta['updated_since'])) {
                    $lastSyncedAt = $meta['updated_since'];
                }

                $hasMore = isset($meta['page'], $meta['last_page']) && $meta['page'] < $meta['last_page'];
            } while ($hasMore);

            $run->update([
                'status' => 'success',
                'finished_at' => now(),
                'processed' => $processed,
                'succeeded' => $succeeded,
                'failed' => $failed,
                'error_sample' => $errorSample,
                'meta' => [
                    'last_synced_at' => $lastSyncedAt,
                ],
            ]);
        } catch (\Throwable $e) {
            $run->update([
                'status' => 'failed',
                'finished_at' => now(),
                'processed' => $processed,
                'succeeded' => $succeeded,
                'failed' => $failed,
                'error_sample' => $errorSample,
                'meta' => [
                    'exception' => $e->getMessage(),
                ],
            ]);
            Log::channel($logChannel)->error('sync import payments exception', [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
