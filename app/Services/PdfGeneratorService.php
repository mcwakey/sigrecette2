<?php
namespace App\Services;
use App\Contracts\PdfGeneratorInterface;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PrintNameEnums;
use App\Helpers\Constants;
use App\Models\Commune;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PrintFile;
use App\Models\StockTransfer;
use App\Models\Taxpayer;
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use ZipArchive;

class PdfGeneratorService implements PdfGeneratorInterface
{
    public function __construct(private  Commune|null $commune = null,
                                private  QrcodeGeneratorService $qrcodeGeneratorService)
    {
        $this->commune = Commune::first();
    }

    /**
     * @param int|null $action
     */
    public function generateInvoicePdf(array $data, string $templateName, int $action = null,$is_relance=false): array
    {

        $data = Invoice::retrieveByUUIDs($data);
        usort($data, function ($a, $b) {
            $codeA = $a->taxpayer_taxable->taxable->tax_label->code;
            $codeB = $b->taxpayer_taxable->taxable->tax_label->code;
            return strcmp($codeA, $codeB);
        });
        if ($data && count($data) == 1 && $this->checkIfCommuneIsNotNull()) {
            /**
             * @var Invoice $data
             */
            $default_invoice = $data[0];
            if ($action == 2 || (intval($default_invoice->invoice_no) !== $default_invoice->id)) {
                $action = 2;
                $invoice = Invoice::find($default_invoice->invoice_no);
            }
            $filename = "Avis-" . (isset($invoice) ? $invoice->invoice_no : $default_invoice->invoice_no) . '-' . date('Ymd_His') . ".pdf";
            if ($action == null) {
                $action = 1;
                $pdf = PDF::loadView(
                    "exports." . $templateName,
                    ['data' => $default_invoice, 'action' => $action, "commune" => $this->commune,
                    'qrcodeSvg' =>$this->qrcodeGeneratorService->generate(
                        route('invoices.show', [$default_invoice]),
                        $this->commune->getImageUrlAttribute()
                    ),
                    'is_relance'=>$is_relance,])
                    ->stream($filename);
                $invoice = $default_invoice;
            } else {
                $pdf = PDF::loadView(
                    "exports." . $templateName,
                    ['data' => $default_invoice, 'action' => $action,
                        'invoice' => $invoice,
                        "commune" => $this->commune,
                     'qrcodeSvg' =>$this->qrcodeGeneratorService->generate($invoice->invoice_no)
                        ,
                        'is_relance'=>$is_relance,
                    ])
                    ->stream($filename);
            }
            if (isset($invoice) && $invoice->edition_state == null) {
                $invoice->edition_state = "PRINT";
                $invoice->save();
            }
            return ['success' => true, 'pdf' => $pdf,"filename" => $filename];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    /**
     * @param int|null $action
     */
    public function generateInvoiceListPdf(array $data, string $template, int $action = null): array
    {
        if ($action == 42) {
            $data = Invoice::retrieveByUUIDs($data, 'payment');
        } else {
            $data = Invoice::retrieveByUUIDs($data);
        }
        if ($this->checkIfCommuneIsNotNull() && $data !== []) {
            $filename = "Avis-liste-" . count($data) . '-' . date('Ymd_His') . ".pdf";
            $pdf =
                PDF::loadView(
                    "exports." . $template,
                    ['data' => $data, 'titles' => $this->generateTitleWithAction($action),
                        "commune" => $this->commune, "action" => $action])
                    ->setPaper('a4', 'landscape')
                    ->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }

    public function downloadReceipt($data)
    {
        $data = json_decode($data, true);
        $filename = "receipt-" . $data[2] . '-' . Str::random(8) . ".pdf";
        return PDF::loadView('exports.payments', ['data' => $data])
            ->stream($filename);
    }
    public function generateTitleWithAction($action = null): array
    {
        $base_array = [
            "N° Avis des sommes à payer",
            "Date d’émission",
            "N° OR",
            "NIC",
            "Nom ou raison sociale du contribuable",
            "N° de Téléphone",
            "Zone fiscale",
            "Canton- Quartier - ville - Adresse complète",
            "Coordonnées GPS",
            "Somme due",
            "PC/ Rejeté",
            "TOTAL DU PRESENT BORDEREAU",
            "TOTAL GENERAL DU PRECEDENT BORDEREAU",
            "TOTAL GENERAL DU PRESENT BORDEREAU (A reporter)",
            "Arrêté le présent bordereau  à la somme de",
            "Bordereau Journal des avis des sommes à payer"
        ];
        switch ($action) {
            case 1:
                break;
            case 2:
                $base_array[0] = "N° Avis de réduction ou d’annulation";
                $base_array[3] = "N° Avis réduit ou annulé";
                $base_array[2] = "N° OR d’annulation ou réduction";
                $base_array[9] = "Somme réduite ou annulée";
                $base_array[11] = "TOTAL DU PRESENT BORDEREAU D’ANNULATION";
                $base_array[12] = "TOTAL GENERAL DU PRECEDENT BORDEREAU D’ANNULATION";
                $base_array[13] = "TOTAL GENERAL DU PRESENT BORDEREAU   D’ANNULATION";
                $base_array[14] = "Arrêté le présent bordereau journal de réduction ou d’annulation à la somme de";
                $base_array[15] = "Bordereau journal des avis de réduction ou d’annulation";
                break;
            case 5:
                $base_array = [
                    "Date réception/ encaissement",
                    "N° OR",
                    "N° Avis des sommes à payer",
                    "NIC",
                    "Nom ou raison sociale du contribuable",
                    "Coordonnées GPS",
                    "Imputation",
                    "Montant émis",
                    "N° quittance",
                    "Montant encaissé/ annulé",
                    "Reste à recouvrer",
                    "Journal des avis des sommes à payer confiés par le receveur"
                ];
                break;
            default:
                $base_array[0] = "N° Avis de réduction ou d’annulation";
                $base_array[2] = "N° Avis réduit ou annulé";
                $base_array[3] = "N° OR d’annulation ou réduction";
                $base_array[9] = "Somme réduite ou annulée";
                $base_array[11] = "TOTAL DU PRESENT BORDEREAU D’ANNULATION";
                $base_array[12] = "TOTAL GENERAL DU PRECEDENT BORDEREAU D’ANNULATION";
                $base_array[13] = "TOTAL GENERAL DU PRESENT BORDEREAU   D’ANNULATION";
                $base_array[14] = "Arrêté le présent bordereau journal de réduction ou d’annulation à la somme de";
                $base_array[15] = "Bordereau journal des avis de réduction ou d’annulation";
                break;
        }
        return $base_array;
    }
    private function checkIfCommuneIsNotNull(): bool
    {
        return $this->commune != null;
    }
    public function generateStateValueCollectorPdf($data, string $template, $action): array
    {
        if ($this->checkIfCommuneIsNotNull()) {
            $filename = "StateValueCollector" . Str::random(8) . ".pdf";
            $pdf = PDF::loadView("exports." . $template, ['data' => $data, "commune" => $this->commune])->setPaper('a4', 'landscape')->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    public function generateStateValueReciepientPdf($data, $template, $action): array
    {
        if ($this->checkIfCommuneIsNotNull()) {
            $filename = "StateValueCollector" . Str::random(8) . ".pdf";
            $pdf = PDF::loadView("exports." . $template, ['data' => $data, "commune" => $this->commune])->setPaper('a4', 'landscape')->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    public function generataxpayerFormPdf($data, string $template): array
    {

        if ($this->checkIfCommuneIsNotNull()&& count($data) > 1) {
            $data = Taxpayer::getInvoiceAndPayments($data[0]);
            $filename = "Fiche-contribuable" . Str::random(8) . ".pdf";
            $pdf = PDF::loadView("exports." . $template, ['data' => $data, "commune" => $this->commune])->setPaper('a4')->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    public function generateLedgersPdf(string $template): array
    {
        $data = Payment::getPrintData();
        $filename = 'Livre-journal_de_Regie.pdf';
        $pdf = PDF::loadView("exports." . $template, ['data' => $data, "commune" => $this->commune, 'logo_url' => $this->commune->getImageUrlAttribute()])->setPaper('a4', 'landscape')->stream($filename);
        return ['success' => true, 'pdf' => $pdf];
    }
    public function generateBordereauListPdf(string $templateName, $action, PrintFile|null $printFile = null)
    {
        $type = null;
        if ($action == 1) {
            $type = PrintNameEnums::BORDEREAU;
        } elseif ($action == 2) {
            $type = PrintNameEnums::BORDEREAU_REDUCTION;
        }
        if ($type != null && $printFile == null) {
            $data = Invoice::getPrintData([InvoiceStatusEnums::PENDING], $type);
            if (count($data) > 0) {
                $total = 0;
                foreach ($data as $datum) {
                    if ($type === PrintNameEnums::BORDEREAU) {
                        $total += $datum->amount;
                    } else {
                        $total += $datum->reduce_amount;
                    }
                }
                $printFile = PrintFile::createPrintFile($type, $data, $total);
            }
        }
        if ($printFile != null && $this->checkIfCommuneIsNotNull()) {
            $data = $printFile->invoices()->get();
            //
            if (count($data) > 0) {
                $filename = $type . "-" . date('Ymd_His') . ".pdf";
                $pdf = PDF::loadView(
                    "exports." . $templateName,
                    ['data' => $data, 'titles' => $this->generateTitleWithAction($action),
                        "commune" => $this->commune, "action" => $action, 'print' => $printFile])
                    ->setPaper('a4', 'landscape')
                    ->stream($filename);
                foreach ($data as $invoice) {
                    if ($invoice->edition_state == "PRINT") {
                        $invoice->edition_state = "bPRINT";
                        $invoice->status = InvoiceStatusEnums::PENDING;
                        $invoice->save();
                    }
                }
                return ['success' => true, 'pdf' => $pdf];
            }
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    /**
     * @param int|null $action
     */
    public function generateJournalInvoiceListPdf(array $data, string $template, int $action = null): array
    {
        $data = Invoice::getPrintData(
            [InvoiceStatusEnums::CANCELED,
                InvoiceStatusEnums::REDUCED,
                InvoiceStatusEnums::APPROVED,
                InvoiceStatusEnums::APPROVED_CANCELLATION]);
        if ($this->checkIfCommuneIsNotNull() && count($data) > 0) {
            $filename = "Journal_des_avis_des_sommes_à_payer_confiés_par_le_receveur" . "-" . date('Ymd_His') . ".pdf";
            $pdf = PDF::loadView("exports." . $template, ['data' => $data, 'titles' => $this->generateTitleWithAction($action), "commune" => $this->commune, "action" => $action])->setPaper('a4', 'landscape')->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    /**
     * @param int|null $action
     */
    public function generateInvoiceRegistrePdf(string $template, int $action = null): array
    {
        $data = Invoice::getPrintData(
            [InvoiceStatusEnums::CANCELED,
                InvoiceStatusEnums::REDUCED,
                InvoiceStatusEnums::APPROVED,
                InvoiceStatusEnums::APPROVED_CANCELLATION]);
        if ($this->checkIfCommuneIsNotNull() && count($data) > 0) {
            $filename = "Registre-journal-des-avis-distribués" . Str::random(8) . ".pdf";
            $pdf = PDF::loadView("exports." . $template, ['data' => $data, 'titles' => $this->generateTitleWithAction($action), "commune" => $this->commune, "action" => $action])->setPaper('a4', 'landscape')->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    public function generateInvoiceDistribtionOrInvoiceRecouvrementPdf(array|PrintFile $data, string $template, int $action = null, User $user = null): array
    {
        $type = null;
        if ($action == 4) {
            $type = PrintNameEnums::FICHE_DE_DISTRIBUTION_DES_AVIS;
        } elseif ($action == 41) {
            $type = PrintNameEnums::FICHE_DE_RECOUVREMENT_DES_AVIS_DISTRIBUES;
        }
        if ($data instanceof PrintFile) {
            $printFile = $data;
            $data = $data->invoices()->get();
        } elseif ($type != null && $user instanceof User) {
            $data = Invoice::filterByType(Invoice::retrieveByUUIDs($data), $type);
            if ($data !== []) {
                $printFile = PrintFile::createPrintFile($type, $data, 0, $user);
                if ($type === PrintNameEnums::FICHE_DE_DISTRIBUTION_DES_AVIS) {
                    DB::transaction(function () use ($data) {
                        foreach ($data as $item) {
                            $item->ondistributionprint = true;
                            $item->save();
                        }
                    });
                } else {
                    DB::transaction(function () use ($data) {
                        foreach ($data as $item) {
                            $item->onrecoveryprint = true;
                            $item->save();
                        }
                    });
                }
            }
        } else {
            $data = [];
        }
        if ($this->checkIfCommuneIsNotNull() && isset($printFile) && count($data) > 0) {
            $filename = $type . "-" . $printFile->id . "-" . date('Ymd_His') . ".pdf";
            $pdf = PDF::loadView("exports." . $template, ['data' => $data, 'titles' => $this->generateTitleWithAction($action), "commune" => $this->commune, "action" => $action, 'print' => $printFile, 'agent' => $user])->setPaper('a4', 'landscape')->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    /**
     * @param int|null $action
     */
    public function generateInvoiceTypeTwoListPdf(array $data, string $template, int $action = null): array
    {
        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");
        $data = Invoice::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->where('type', '=', Constants::INVOICE_TYPE_COMPTANT)
            ->where('status', '=', InvoiceStatusEnums::APPROVED)->get();
        if ($this->checkIfCommuneIsNotNull() && count($data) > 0) {
            $filename = "Registre-journal_des_déclarations_préalables_des_usagers" . Str::random(8) . ".pdf";
            $pdf = PDF::loadView("exports." . $template, ['data' => $data, 'titles' => $this->generateTitleWithAction($action), "commune" => $this->commune, "action" => $action])->setPaper('a4', 'landscape')->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    public function generateStateAcountIvCollectorPdf($data, string $template): array
    {
        if(count($data) > 2) {
            $user = User::find($data[0]);
            $period = $data[1];
            $data = StockTransfer::buildAndGetStockTransferWithQuery($period);
            $filename = "ETAT_DE_COMPTABILITE_DES_VALEURS_INACTIVES_DU_COLLECTEUR" . Str::random(8) . ".pdf";
            $pdf = PDF::loadView("exports." . $template, ['data' => $data, "commune" => $this->commune, 'user' => $user, 'period' => $data[1]])->setPaper('a4', 'landscape')->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
}
