<?php
namespace App\Http\Controllers;
use App\DataTables\PrintablesDataTable;
use App\Helpers\PdfGenerator;
use App\Models\Invoice;
use App\Models\PrintFile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PrintController extends Controller
{
    public function __construct(private PdfGenerator $pdfGenerator)
    {
    }
    public function index(PrintablesDataTable $printablesDataTable)
    {
        return $printablesDataTable->render('pages/printables.list');
    }
    /**
     * @param $data
     * @return RedirectResponse|Response|mixed
     */
    public function download($data, $type = null, $action = null, User $id = null)
    {
        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }
        $data = json_decode($data, true);
        $result = $this->processType($type, $data, $action, $id);
        if ($result['success']) {
            return $result['pdf'];
        }
        return back()->with('error', $result['message']);
    }
    /**
     * @return RedirectResponse|Response|mixed
     */
    public function downloadWithPrintData(PrintFile $printFile, $type = null, $action = null)
    {
        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }
        $result = $this->processType($type, $printFile, $action);
        if ($result['success']) {
            session()->flash('status', 'Ficher Imprimer avec success.');
            return $result['pdf'];
        }
        session()->flash('status', "Erreur lors de la géneration du ficher");
        return back()->with('error', $result['message']);
    }

    /**
     * @param $type
     * @param $data
     * @param $action
     */
    public function processType($type, $data, $action, User $user = null): array
    {
        switch ($type) {
            case 1:
                return $this->pdfGenerator->downloadReceipt($data);
            case 2:
                if ($action == 3) {
                    return $this->pdfGenerator->generateInvoiceRegistrePdf('invoices-registre', $action);
                } elseif ($action == 4) {
                    return $this->pdfGenerator->generateInvoiceDistribtionOrInvoiceRecouvrementPdf($data, 'invoices-distribution', $action, $user);
                } elseif ($action == 41) {
                    return $this->pdfGenerator->generateInvoiceDistribtionOrInvoiceRecouvrementPdf($data, 'invoices-recouvrement', $action, $user);
                } elseif ($action == 5) {
                    return $this->pdfGenerator->generateJournalInvoiceListPdf($data, 'invoices-journal-receveur', $action);
                } elseif ($action == 42) {
                    return $this->pdfGenerator->generateInvoiceListPdf($data, 'invoices-recouvrement', $action);
                } elseif ($action == 77) {
                    return $this->pdfGenerator->generateInvoiceTypeTwoListPdf($data, 'registre-journal-des-declarations-prealables-des-usagers', $action);
                } elseif ($action == 1 || $action == 2) {
                    if ($data instanceof PrintFile) {
                        return $this->pdfGenerator->generateBordereauListPdf('invoices-list', $action, $data);
                    } else {
                        return $this->pdfGenerator->generateBordereauListPdf('invoices-list', $action);
                    }
                }
            case 11:
                return $this->pdfGenerator->generataxpayerFormPdf($data, 'taxpayer-form');
            case 6:
                return $this->pdfGenerator->generateStateAcountIvCollectorPdf($data, 'state-account-iv-collectorc');
            case 7:
                return $this->pdfGenerator->generateStateValueCollectorPdf($data, 'state-account-iv-receveur', $action);
            case 8:
                return $this->pdfGenerator->generateStateValueCollectorPdf($data, 'state-versement-collecteur', $action);
            case 9:
                return $this->pdfGenerator->generateStateValueCollectorPdf($data, 'state-versement-regisseur', $action);
            case 16:
                return $this->pdfGenerator->generateStateValueCollectorPdf($data, "state-versement-regisseur-comptant", $action);
            case 10:
                return $this->pdfGenerator->generateLedgersPdf('livre-journal-regie');
            case 15:
                return $this->pdfGenerator->generateStateValueCollectorPdf($data, 'state-iv-regisseur', $action);
            default:
                return $this->pdfGenerator->generateInvoicePdf($data, 'invoices', $action);
        }
    }
    public function downloadMultipleInvoicePdf(int $action = null): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $uuid = Invoice::getPrintableUuid();
        $zip = new ZipArchive();
        $zipFileName = storage_path('app/public/multiples_pdf.zip');
        if (file_exists($zipFileName)) {
            unlink($zipFileName);
        }
        if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($uuid as $invoiceUid){
                $result =$this->pdfGenerator->generateInvoicePdf([$invoiceUid],'invoices',$action);
                if($result['success']){
                    $filename = "invoice_{$invoiceUid}_" . date('Ymd_His') . ".pdf";
                    $zip->addFromString($result['filename'], $result['pdf']);
                }else {
                    \Log::warning("Impossible de générer le PDF pour l'UUID: {$invoiceUid}");
                }
            }
            $zip->close();
        }else {
            abort(500, "Impossible de créer l'archive ZIP.");
        }
        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }
}
