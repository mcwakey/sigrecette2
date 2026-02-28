<?php
namespace App\Jobs;

use App\Http\Resources\SyncInvoiceResource;
use App\Models\Invoice;
use App\Models\SyncRun;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncExportInvoicesJob implements ShouldQueue
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
        $chunkSize = config('sync.chunk_size', 200);
        $timeout = config('sync.timeout', 15);
        $retries = config('sync.retries', 3);
        $logChannel = config('sync.log_channel', 'sync');

        if (!$baseUrl || !$token) {
            Log::channel($logChannel)->warning('sync export invoices skipped: missing remote config');
            return;
        }

        $lastSuccess = SyncRun::where('direction', 'export')
            ->where('entity', 'invoices')
            ->where('status', 'success')
            ->orderByDesc('finished_at')
            ->first();

        $cursor = $lastSuccess?->finished_at?->toISOString();

        $run = SyncRun::create([
            'direction' => 'export',
            'entity' => 'invoices',
            'status' => 'started',
            'started_at' => now(),
            'cursor' => $cursor,
        ]);

        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::createFromDate((int) $activeYear->name, 1, 1)->startOfDay();
        $endOfYear = Carbon::createFromDate((int) $activeYear->name, 12, 31)->endOfDay();

        $query = Invoice::query()
            ->where('status', 'APPROVED')
            ->where('validity', 'VALID')
            ->where(function ($q) use ($startOfYear, $endOfYear) {
                $q->whereBetween('from_date', [$startOfYear, $endOfYear])
                    ->orWhereBetween('to_date', [$startOfYear, $endOfYear])
                    ->orWhereBetween('created_at', [$startOfYear, $endOfYear]);
            });

        if ($cursor) {
            $query->where('updated_at', '>=', Carbon::parse($cursor));
        }

        $maxUpdatedAt = null;
        $processed = 0;
        $succeeded = 0;
        $failed = 0;
        $errorSample = [];

        try {
            $query->with(['taxpayer', 'invoiceitems'])
                ->orderBy('updated_at')
                ->chunkById($chunkSize, function ($invoices) use (
                    $baseUrl,
                    $token,
                    $timeout,
                    $retries,
                    $logChannel,
                    &$processed,
                    &$succeeded,
                    &$failed,
                    &$errorSample,
                    &$maxUpdatedAt
                ) {
                    $payload = SyncInvoiceResource::collection($invoices)->resolve();
                    $processed += count($payload);

                    foreach ($invoices as $invoice) {
                        if (!$maxUpdatedAt || $invoice->updated_at->gt($maxUpdatedAt)) {
                            $maxUpdatedAt = $invoice->updated_at;
                        }
                    }

                    $response = Http::timeout($timeout)
                        ->retry($retries, 500)
                        ->withToken($token)
                        ->post(rtrim($baseUrl, '/').'/v1/sync/invoices', [
                            'invoices' => $payload,
                        ]);

                    if ($response->failed()) {
                        $failed += count($payload);
                        $errorSample[] = [
                            'reason' => 'remote_error',
                            'status' => $response->status(),
                        ];
                        Log::channel($logChannel)->error('sync export invoices failed', [
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                        return false;
                    }

                    $succeeded += count($payload);
                    $responseBody = $response->json();
                    if (is_array($responseBody) && !empty($responseBody['errors'])) {
                        $errorSample = array_slice(array_merge($errorSample, $responseBody['errors']), 0, 50);
                    }
                    Log::channel($logChannel)->info('sync export invoices batch', [
                        'count' => count($payload),
                    ]);

                    return true;
                });

            $run->update([
                'status' => 'success',
                'finished_at' => now(),
                'processed' => $processed,
                'succeeded' => $succeeded,
                'failed' => $failed,
                'error_sample' => $errorSample,
                'meta' => [
                    'last_synced_at' => $maxUpdatedAt?->toISOString(),
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
            Log::channel($logChannel)->error('sync export invoices exception', [
                'message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
