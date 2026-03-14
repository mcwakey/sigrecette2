<?php

namespace App\Jobs;

use App\Contracts\PdfGeneratorInterface;
use App\Contracts\PrintServiceInterface;
use App\Enums\PrintNameEnums;
use App\Models\PrintFile;
use App\Models\User;
use App\Notifications\FileReadyNotification;
use App\Services\DownloadInvoiceZipService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadInvoiceZipJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(private $dto, private User $user)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $downloadService = app(DownloadInvoiceZipService::class);
        $result = $downloadService->execute($this->dto);
        if ($result['success']) {
            $this->user->notify(new FileReadyNotification($result['file_name'], $this->user->id));
        }


    }
}
