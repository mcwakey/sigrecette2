<?php

namespace App\Actions;

use App\Contracts\ExceptionServiceInterface;
use App\Contracts\PdfGeneratorInterface;
use App\Helpers\Constants;
use App\Models\Invoice;
use Spatie\Async\Pool;
use Illuminate\Support\Facades\Log;
use ZipArchive;
class DownloadMultipleInvoiceAction
{
    public function execute($action)
    {
        $exceptionService = resolve(ExceptionServiceInterface::class);

        try {
            $pdfGenerator = app(PdfGeneratorInterface::class);
            $zip = new ZipArchive();
            $zipFileName = storage_path('app/public/multiples_avis.zip');
            $previousUrl = url()->previous();
            $urlParts = parse_url($previousUrl);
            $state = null;
            parse_str($urlParts['query'] ?? '', $queryParams);
            if (isset($queryParams['state']) && array_key_exists($queryParams['state'], Constants::INVOICE_STATE_PRINTABLE_MAP)) {
                $state = Constants::INVOICE_STATE_PRINTABLE_MAP[$queryParams['state']];
            }
            $uuid = Invoice::getPrintableUuid($state ?? null);
            if (file_exists($zipFileName)) {
                unlink($zipFileName);
            }
            if (count($uuid) == 0) {
                return back()->with('error', 'no Data');
            } else {
                if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                    $pool = Pool::create();
                    foreach ($uuid as $invoiceUid) {
                        $result =$pdfGenerator->generateInvoicePdf([$invoiceUid], 'invoices', $action);
                        if ($result['success']) {
                            $zip->addFromString($result['filename'], $result['pdf']);
                        } else {
                            Log::warning("Impossible de générer le PDF pour l'UUID: {$invoiceUid}");
                        }
                    }
                    $pool->wait();
                    $zip->close();
                } else {
                    abort(500, "Impossible de créer l'archive ZIP.");
                }
                return response()->download($zipFileName)->deleteFileAfterSend(true);
            }
        }catch (\Throwable $e) {
            $code =$exceptionService->getStatusCode($e);
            return view("errors.{$code}", [
                "code" => $code,
                "message" => $exceptionService->getMessage($e)
            ]);
        }

    }
}
