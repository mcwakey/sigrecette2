<?php

namespace App\Jobs;

use App\Services\MobilePayment\MobilePaymentService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExpireStaleMobilePaymentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(MobilePaymentService $service): void
    {
        $count = $service->markExpired();

        if ($count > 0) {
            Log::channel('daily')->info("Expired {$count} stale mobile payment transactions");
        }
    }
}
