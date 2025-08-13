<?php

namespace App\Jobs;

use App\Contracts\PdfGeneratorInterface;
use App\Contracts\PrintServiceInterface;
use App\Enums\PrintNameEnums;
use App\Models\PrintFile;
use App\Models\User;
use App\Notifications\FileReadyNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LongPrintTaskJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(private string $printType,private $data, private $action, private User $user)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result['success']=false;
        switch ($this->printType) {
            case PrintNameEnums::BORDEREAU:
                $pdfGenerator = app(PdfGeneratorInterface::class);
                if ($this->data instanceof PrintFile) {
                    $result= $pdfGenerator->generateBordereauListPdf('invoices-list', $this->action, $this->data);
                } else {
                    $result= $pdfGenerator->generateBordereauListPdf('invoices-list', $this->action);
                }
                break;
            case PrintNameEnums::MULTIPLE_INVOICE:
                $printService = app(PrintServiceInterface::class);
                $zipFileName=$printService->downloadMultipleInvoice($this->action);
                if ($zipFileName){
                    $result['file_name']=$zipFileName;
                    $result['success']=true;
                }
                break;
            default:
                throw new \Exception("Type d'impression inconnu : {$this->printType}");
        }
        if ($result['success']) {
            $this->user->notify(new FileReadyNotification($result['file_name'], $this->user->id));
        }


    }
}
