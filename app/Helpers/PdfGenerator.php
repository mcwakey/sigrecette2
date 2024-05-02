<?php

namespace App\Helpers;

use App\Contracts\PdfGeneratorInterface;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PrintNameEnums;
use App\Models\Commune;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PrintFile;
use App\Models\Taxpayer;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfGenerator  implements PdfGeneratorInterface
{
    public function __construct( public Commune|null $commune = null)
    {
        $this->commune = Commune::first();
    }

    /**
     * @param array $data
     * @param string $template
     * @param int|null $action
     * @return array
     */
    public function generateInvoiceListPdf(array $data,string $template,int $action=null):array
    {

        //dd($data,$template,$action);
        if($action==42){
            $data=Invoice::retrieveByUUIDs($data,'payment');
        }else{
            $data=Invoice::retrieveByUUIDs($data);
        }
        if ($this->checkIfCommuneIsNotNull()&& count($data)>0) {

            $filename = "Invoice-list-" . Str::random(8) . ".pdf";
            //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,'titles'=>$this->generateTitleWithAction($action),"commune"=> $this->commune,"action"=>$action])->setPaper('a4', 'landscape')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }





    public function downloadMultiple( $data)
    {

        $data = json_decode($data, true);
        foreach ($data as $key => $subdata) {

            if(count($subdata)>4){
                $filename = 'document_' . $key . '.pdf';
                $pdf= PDF::loadView('exports.invoices', ['data' => $subdata])
                    ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
                    ->stream($filename);
                dd($pdf);
            }

        }
        return back();
    }
    public function downloadReceipt( $data)
    {


        $data = json_decode($data, true);

        $filename="receipt-".$data[2].'-'.Str::random(8).".pdf";

        //dd($data);
        $pdf= PDF::loadView('exports.payments', ['data' => $data])
            // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
            ->stream($filename);
        return $pdf;

    }





    public function generateTitleWithAction($action=null): array
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
                $base_array[9]= "Somme réduite ou annulée";
                $base_array[11]= "TOTAL DU PRESENT BORDEREAU D’ANNULATION";
                $base_array[12]= "TOTAL GENERAL DU PRECEDENT BORDEREAU D’ANNULATION";
                $base_array[13]= "TOTAL GENERAL DU PRESENT BORDEREAU   D’ANNULATION";
                $base_array[14]= "Arrêté le présent bordereau journal de réduction ou d’annulation à la somme de";
                $base_array[15]= "Bordereau journal des avis de réduction ou d’annulation";
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
                $base_array[9]= "Somme réduite ou annulée";
                $base_array[11]= "TOTAL DU PRESENT BORDEREAU D’ANNULATION";
                $base_array[12]= "TOTAL GENERAL DU PRECEDENT BORDEREAU D’ANNULATION";
                $base_array[13]= "TOTAL GENERAL DU PRESENT BORDEREAU   D’ANNULATION";
                $base_array[14]= "Arrêté le présent bordereau journal de réduction ou d’annulation à la somme de";
                $base_array[15]= "Bordereau journal des avis de réduction ou d’annulation";
                break;
        }

        return $base_array;
    }



    /**
     * @return bool
     */
    private function checkIfCommuneIsNotNull():bool{
        if ($this->commune==null) {return false;}
        return true;
    }


    /**
     * @param array $data
     * @param string $templateName
     * @param int|null $action
     * @return array
     */
    public function generateInvoicePdf(array $data,string $templateName,int $action=null ):array
    {

        $data=Invoice::retrieveByUUIDs($data);
        usort($data, function ($a, $b) {
            $codeA = $a->taxpayer_taxable->taxable->tax_label->code;
            $codeB = $b->taxpayer_taxable->taxable->tax_label->code;
            return strcmp($codeA, $codeB);
        });

        if ( $data!==false&&count($data)==1&&$this->checkIfCommuneIsNotNull()) {
            $default_invoice =$data[0];

            $filename="Invoice-".Str::random(8).".pdf";

            if($action==2 || ( intval($default_invoice->invoice_no) !==$default_invoice->id)){
                $action=2;
                $invoice = Invoice::find( $default_invoice->invoice_no);

            }


            if($action ==null){

                $action=1;
                $pdf= PDF::loadView(
                    "exports.".$templateName, ['data' => $default_invoice,'action'=>$action,"commune"=> $this->commune])
                    // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)

                    //->setPaper('a4', 'landscape')
                    ->stream($filename);
            }else{
                $pdf= PDF::loadView(
                    "exports.".$templateName, ['data' => $default_invoice,'action'=>$action,'invoice'=> $invoice,"commune"=> $this->commune])
                    // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
                    //->setPaper('a4', 'landscape')
                    ->stream($filename);
            }


            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }



    public function generateStateValueCollectorPdf($data, string $template, $action):array
    {
        //dd($data);
       // if ($this->checkInvoiceListDataUniformity($data,$expectedDataSize)&& $this->checkIfCommuneIsNotNull()) {

            $filename = "StateValueCollector" . Str::random(8) . ".pdf";
            //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,"commune"=> $this->commune])->setPaper('a4', 'landscape')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
       // }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }




    public function generateStateValueReciepientPdf($data, $template, $action):array
    {
        // if ($this->checkInvoiceListDataUniformity($data,$expectedDataSize)&& $this->checkIfCommuneIsNotNull()) {

        $filename = "StateValueCollector" . Str::random(8) . ".pdf";
        //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
        $pdf = PDF::loadView("exports.".$template, ['data' => $data,"commune"=> $this->commune])->setPaper('a4', 'landscape')->stream($filename);

        return ['success' => true, 'pdf' => $pdf];
        // }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }



    public function generataxpayerFormPdf($data, string $template):array
    {
        $data=Taxpayer::getInvoiceAndPayments($data[0]);
        if ($this->checkIfCommuneIsNotNull()) {

            //dd($data);
            $filename = "Fiche-contribuable" . Str::random(8) . ".pdf";
            //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,"commune"=> $this->commune])->setPaper('a4')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    public function generateLedgersPdf( string $template):array
    {

        $data = Payment::getPrintData();
        $filename = "Livre-journal_de_Regie". ".pdf";
        $pdf = PDF::loadView("exports.".$template, ['data' => $data,"commune"=> $this->commune])->setPaper('a4', 'landscape')->stream($filename);

        return ['success' => true, 'pdf' => $pdf];
        // }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }

    public function generateBordereauListPdf( string $templateName, $action)
    {
        $type = $action==1?PrintNameEnums::BORDEREAU:PrintNameEnums::BORDEREAU_REDUCTION;
        $data=Invoice::getPrintData($type);
        if( count($data)>0){
            $total=0;
            foreach ($data as $datum){
                if($type==PrintNameEnums::BORDEREAU){
                    $total+=$datum->amount;
                }else{
                    $total+=$datum->reduce_amount;
                }
            }
            $last_print = PrintFile::getLastPrintFileByType($type);
            $print_data = [
                'name' =>PrintNameEnums::BORDEREAU,
                'last_sequence_number' => $last_print==null?1:$last_print->last_sequence_number+1,
                'total_last_sequence' => $total
            ];
            $total_last_sequence=$last_print==null?0:$last_print->total_last_sequence;
            $print= PrintFile::create($print_data);

            //dd($data,$print);
            $print = Invoice::addPrintableToInvoices($data,$print);
            $data= Invoice::where("print_file_id",$print->id)->get();
            if ($this->checkIfCommuneIsNotNull()&& count($data)>0) {

                $filename = $type."-" . Str::random(8) . ".pdf";
                //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
                $pdf = PDF::loadView("exports.".$templateName, ['data' => $data,'titles'=>$this->generateTitleWithAction($action),"commune"=> $this->commune,"action"=>$action,'print'=>$print,'total_last_sequence'=>$total_last_sequence])->setPaper('a4', 'landscape')->stream($filename);

                return ['success' => true, 'pdf' => $pdf];
            }

        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }

    public function generateWithPrintdata(string $string, $action, $data)
    {
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
}
