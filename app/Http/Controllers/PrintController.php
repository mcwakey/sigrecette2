<?php

namespace App\Http\Controllers;

use App\Actions\DownloadMultipleInvoiceAction;
use App\Actions\PrintWithData;
use App\Actions\PrintWithoutData;
use App\DataTables\PrintablesDataTable;
use App\Enums\InvoiceStatusEnums;
use App\Models\Commune;
use App\Models\Invoice;
use App\Models\PrintFile;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use ZipArchive;


class PrintController extends Controller
{

    public function index(PrintablesDataTable $printablesDataTable)
    {
        return $printablesDataTable->render('pages/printables.list');
    }
    /**
     * @param $data
     * @return RedirectResponse|Response|mixed
     */
    public function download(PrintWithoutData $actionExecute,$data = null, $type = null, $action = null, User $id = null)
    {
        return $actionExecute->execute($data,$type,$action,$id);
    }
    /**
     * @return RedirectResponse|Response|mixed
     */
    public function downloadWithPrintData(PrintWithData $actionExecute,PrintFile $printFile, $type = null, $action = null)
    {
        return $actionExecute->execute($printFile, $type, $action);
    }


    public function downloadMultipleInvoicePdf(DownloadMultipleInvoiceAction $actionExecute,Request $request,
                                               int $action = null):
    \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse|View
    {

        return $actionExecute->execute($action);
    }

    public function testBordereauTemplate()
    {
        $commune = Commune::first() ?? $this->makeDummyCommune();
        $pdfGenerator = app(\App\Contracts\PdfGeneratorInterface::class);
        $titles = $pdfGenerator->generateTitleWithAction(1);

        $data = Invoice::where('type', 'TITRE')
            ->where('status', InvoiceStatusEnums::PENDING->value)
            ->with(['taxpayer.town.canton', 'taxpayer.zone'])
            ->limit(50)
            ->get();

        $pdf = Pdf::loadView('exports.invoices-list', [
            'data' => $data,
            'titles' => $titles,
            'commune' => $commune,
            'action' => 1,
            'print' => (object) [
                'last_sequence_number' => 0,
                'total_last_sequence' => 0,
            ],
        ])
            ->setPaper('a4', 'landscape')
            ->stream('bordereau-test-' . date('Ymd_His') . '.pdf');

        return $pdf;
    }

    public function testPrintAllPending()
    {
        $commune = Commune::first() ?? $this->makeDummyCommune();

        $invoices = Invoice::where('type', 'TITRE')
            ->where('status', InvoiceStatusEnums::PENDING->value)
            ->whereNull('printed_at')
            ->with([
                'invoiceitems.taxpayer_taxable.taxable.tax_label',
                'taxpayer_taxables.taxable.tax_label',
                'taxpayer.town.canton',
                'taxpayer.zone',
                'taxpayer.category',
                'taxpayer.activity',
                'payments',
            ])
            ->get();

        if ($invoices->isEmpty()) {
            return back()->with('error', 'Aucune facture PENDING TITRE trouvée.');
        }

        $zip = new ZipArchive();
        $fileName = 'test_print_all_pending.zip';
        $zipFilePath = storage_path("app/exports/{$fileName}");

        if (file_exists($zipFilePath)) {
            unlink($zipFilePath);
        }

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Impossible de créer le fichier ZIP.');
        }

        foreach ($invoices as $invoice) {
            $pdfFilename = 'Avis-' . $invoice->invoice_no . '-' . date('Ymd_His') . '.pdf';
            $pdf = Pdf::loadView('exports.invoices', [
                'data' => $invoice,
                'action' => 1,
                'commune' => $commune,
                'qrcodeSvg' => null,
                'is_relance' => false,
            ])->output();

            $zip->addFromString($pdfFilename, $pdf);

            if ($invoice->edition_state == null) {
                $invoice->edition_state = 'PRINT';
            }
            if ($invoice->printed_at == null) {
                $invoice->printed_at = now();
            }
            $invoice->save();
        }

        $zip->close();

        return response()->download($zipFilePath, $fileName)->deleteFileAfterSend(true);
    }

    private function makeDummyCommune(): Commune
    {
        $commune = new Commune();
        $commune->name = 'Commune Test';
        $commune->title = 'Mairie de Test';
        $commune->region_name = 'Région Test';
        $commune->mayor_name = 'Maire Test';
        $commune->phone_number = '00000000';
        $commune->address = 'Adresse Test';
        $commune->treasury_name = 'Trésorerie Test';
        $commune->treasury_address = 'Adresse Trésorerie Test';
        $commune->treasury_rib = '0000000000';
        $commune->qr_code_enabled = false;
        $commune->carry_forward_previous_year = false;
        return $commune;
    }
}
