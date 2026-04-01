<?php

namespace App\Services;

use App\Contracts\PdfGeneratorInterface;
use App\Contracts\PrintServiceInterface;
use App\Enums\PrintNameEnums;
use App\Helpers\Constants;
use App\Jobs\LongPrintTaskJob;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use ZipArchive;
class PrintService implements PrintServiceInterface
{
    public function processType($type, $data, $action, User $user = null): array
    {
        $pdfGenerator = app(PdfGeneratorInterface::class);
        $type = (int) $type;
        $action = (int) $action;
        return match ($type) {
            1   => $this->handleReceipt($data,$pdfGenerator),
            2   => $this->handleTypeTwo($action, $data, $user,$pdfGenerator),
            11  => $pdfGenerator->generataxpayerFormPdf($data, 'taxpayer-form'),
            6   => $pdfGenerator->generateStateAcountIvCollectorPdf($data, 'state-account-iv-collectorc'),
            7   => $pdfGenerator->generateStateValueCollectorPdf($data, 'state-account-iv-receveur', $action),
            8   => $pdfGenerator->generateStateValueCollectorPdf($data, 'state-versement-collecteur', $action),
            9   => $pdfGenerator->generateStateValueCollectorPdf($data, 'state-versement-regisseur', $action),
            16  => $pdfGenerator->generateStateValueCollectorPdf($data, "state-versement-regisseur-comptant", $action),
            10  => $pdfGenerator->generateLedgersPdf('livre-journal-regie'),
            15  => $pdfGenerator->generateStateValueCollectorPdf($data, 'state-iv-regisseur', $action),
            77  => $pdfGenerator->generateInvoicePdf($data, 'invoices', $action, true),
            default => $pdfGenerator->generateInvoicePdf($data, 'invoices', $action),
        };
    }

    private function handleReceipt($data,$pdfGenerator): array
    {
        return $pdfGenerator->downloadReceipt($data);
    }

    private function handleTypeTwo($action, $data, ?User $user,$pdfGenerator): array
    {
        return match ($action) {
            3   => $pdfGenerator->generateInvoiceRegistrePdf('invoices-registre', $action),
            4   => $pdfGenerator->generateInvoiceDistribtionOrInvoiceRecouvrementPdf($data, 'invoices-distribution', $action, $user),
            41  => $pdfGenerator->generateInvoiceDistribtionOrInvoiceRecouvrementPdf($data, 'invoices-recouvrement', $action, $user),
            5   => $pdfGenerator->generateJournalInvoiceListPdf($data, 'invoices-journal-receveur', $action),
            42  => $pdfGenerator->generateInvoiceListPdf($data, 'invoices-recouvrement', $action),
            77  => $pdfGenerator->generateInvoiceTypeTwoListPdf($data, 'registre-journal-des-declarations-prealables-des-usagers', $action),
            1, 2 => $this->generateBordereauListPdf($action, $data,$pdfGenerator),
            default => ['success' => false, 'message' => 'Action inconnue pour type 2'],
        };
    }

    private function generateBordereauListPdf($action, $data,$pdfGenerator): array
    {
        LongPrintTaskJob::dispatch(PrintNameEnums::BORDEREAU->value,$data, $action,auth()->user());
        return ['success' => false, 'message' => 'Le bordereau est en cours de génération. Vous recevrez une notification lorsque le fichier sera prêt.', 'async' => true];
    }
    public function downloadMultipleInvoice($action){
        $pdfGenerator = app(PdfGeneratorInterface::class);
        $zip = new ZipArchive();
        $filename = "multiples_avis" . "-" . date('Ymd_His') . ".zip";
        $zipFileName = storage_path("app/exports/{$filename}");
        $previousUrl = url()->previous();
        $urlParts = parse_url($previousUrl);
        $state = null;
        parse_str($urlParts['query'] ?? '', $queryParams);
        if (isset($queryParams['state']) && array_key_exists($queryParams['state'], Constants::INVOICE_STATE_PRINTABLE_MAP)) {
            $state = Constants::INVOICE_STATE_PRINTABLE_MAP[$queryParams['state']];
        }
        if ($state) {
            $uuid = Invoice::getPrintableUuid($state);
        } else {
            $uuid = Invoice::getPrintableUuid();
        }
        if (file_exists($zipFileName)) {
            unlink($zipFileName);
        }
        if (count($uuid) == 0) {
            return back()->with('error', 'no Data');
        } else {
            if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                foreach (array_chunk($uuid, 50) as $chunk) {
                    foreach ($chunk as $invoiceUid) {
                        $result = $pdfGenerator->generateInvoicePdf([$invoiceUid], 'invoices', $action);
                        if ($result['success']) {
                            $zip->addFromString($result['filename'], $result['pdf']->getContent());
                        } else {
                            Log::warning("Impossible de générer le PDF pour l'UUID: {$invoiceUid}");
                        }
                        unset($result);
                    }
                    gc_collect_cycles();
                }
                $zip->close();
            } else {
                abort(500, "Impossible de créer l'archive ZIP.");
            }
            return $filename;
        }
    }
}
