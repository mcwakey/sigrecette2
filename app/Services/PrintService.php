<?php

namespace App\Services;

use App\Contracts\PdfGeneratorInterface;
use App\Contracts\PrintServiceInterface;
use App\Jobs\LongPrintTaskJob;
use App\Models\PrintFile;
use App\Models\User;

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
        //LongPrintTaskJob::dispatch($action, $data);
        if ($data instanceof PrintFile) {
            $result= $pdfGenerator->generateBordereauListPdf('invoices-list', $action, $data);
        } else {
            $result = $pdfGenerator->generateBordereauListPdf('invoices-list', $action);
        }
        return $result;
    }
}
