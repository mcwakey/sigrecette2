<?php

namespace App\Helpers;

use App\Models\Commune;
use App\Models\Invoice;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfGenerator
{
    public function __construct( public Commune|null $commune = null)
    {
        $this->commune = Commune::first();
    }



    /**
     * @param $type
     * @param $data
     * @return \Illuminate\Http\RedirectResponse|Response|mixed
     */
    public function processType($type, $data,$action)
    {
       dd($data,$type,$action);
        switch ($type) {
            case 1:
                return $this->downloadReceipt($data,$action,'payments');
            case 2:
                if($action=3){
                    return $this->downloadInvoicesList($data,$action,'invoices-registre',15);
                }
                else{
                    return $this->downloadInvoicesList($data,$action,'invoices-list',15);

                }
            //case 3:
            //case 4:return 'invoices-distribution';
            //case 5:return 'invoices-recouvrement';
            //case 6:return 'invoices-registre';
            case 6:return $this->downloadStateValueCollector($data,$action,'state-account-iv-collector',15);
            case 7:return $this->downloadStateValueReciepient($data,$action,'state-account-iv-receveur',15);
            default:
                return $this->downloadInvoice($data,$action,'invoices');

        }
    }
    /**
     * @param $data
     * @param $type
     * @param PdfGenerator $pdfGenerator
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function downloadInvoice($data,$action,$templateName){
        $result = $this->generateInvoicePdf($data,$templateName,$action);
        if ($result['success']) {
            return $result['pdf'];
        }

        return back()->with('error', $result['message']);
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


    /**
     * @param $data
     * @param $type
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function downloadInvoicesList($data,$action, $templateName,$expectedDataSize = 14)
    {

        $result = $this->generateInvoiceListPdf($data,$templateName,$action,$expectedDataSize);
        //dd($data);
        if ($result['success']) {
            return $result['pdf'];
        }

        return back()->with('error', $result['message']);
    }

    /**
     * @param array $data
     * @param string $template
     * @param int|null $action
     * @return array
     */
    public function generateInvoiceListPdf(array $data,string $template,int $action=null,$expectedDataSize):array
    {

        //dd($data,$template,$action);
        if ($this->checkInvoiceListDataUniformity($data,$expectedDataSize)&& $this->checkIfCommuneIsNotNull()) {

            $filename = "Invoice-list-" . Str::random(8) . ".pdf";
            //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,'titles'=>$this->generateTitleWithAction($action),"commune"=> $this->commune])->setPaper('a4', 'landscape')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
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
                $base_array[2] = "N° Avis réduit ou annulé";
                $base_array[3] = "N° OR d’annulation ou réduction";
                $base_array[9]= "Somme réduite ou annulée";
                $base_array[11]= "TOTAL DU PRESENT BORDEREAU D’ANNULATION";
                $base_array[12]= "TOTAL GENERAL DU PRECEDENT BORDEREAU D’ANNULATION";
                $base_array[13]= "TOTAL GENERAL DU PRESENT BORDEREAU   D’ANNULATION";
                $base_array[14]= "Arrêté le présent bordereau journal de réduction ou d’annulation à la somme de";
                $base_array[15]= "Bordereau journal des avis de réduction ou d’annulation";
                break;
            case 3:
                $base_array[15]= "Journal des avis des sommes à payer confiés par le receveur";
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
     * @param $data
     * @return bool
     */
    private function checkInvoiceListDataUniformity($data,int $expectedDataSize): bool
    {

        foreach ($data as $item) {
            if (count($item) !== $expectedDataSize) {
                return false;
            }
        }

        return true;
    }


    /**
     * @return bool
     */
    private function checkIfCommuneIsNotNull():bool{
        if ($this->commune==null) {return false;}
        return true;
    }
    /**
     * @param $data
     * @return bool
     */
    public function checkInvoiceDataUniformity($data): bool
    {


        $expectedDataSize = 14;
        $expectedSubDataSize = 9;

        $firstSubDataValue = "";


        if (count($data) !== $expectedDataSize) {
            return false;
        }


        if (isset($data[12]) && is_array($data[12])) {


            foreach ($data[12] as $index => $item) {


                if ($index === 0) {
                    $firstSubDataValue = $item[1];
                }

                if (count($item) !== $expectedSubDataSize || $firstSubDataValue !== $item[1]) {
                    //dd($item , $expectedSubDataSize ,$firstSubDataValue, $item[1]);
                    return false;
                }
                //dd(isset($data[12]) && is_array($data[12]));
            }
        }

        return true;
    }

    /**
     * @param array $data
     * @param string $templateName
     * @return array
     */
    public function generateInvoicePdf(array $data,string $templateName,int $action=null ):array
    {

        $data=Invoice::retrieveByUUIDs($data);

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

    private function downloadStateValueCollector($data, $action, string$templateName,$expectedDataSize)
    {
        $result = $this->generateStateValueCollectorPdf($data,$templateName,$action,$expectedDataSize);
        //dd($data);
        if ($result['success']) {
            return $result['pdf'];
        }

        return back()->with('error', $result['message']);
    }

    private function generateStateValueCollectorPdf($data, string $template, $action, $expectedDataSize)
    {
       // if ($this->checkInvoiceListDataUniformity($data,$expectedDataSize)&& $this->checkIfCommuneIsNotNull()) {

            $filename = "StateValueCollector" . Str::random(8) . ".pdf";
            //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,"commune"=> $this->commune])->setPaper('a4', 'landscape')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
       // }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }

    private function downloadStateValueReciepient($data, $action, string $template, int $expectedDataSize)
    {
        $result = $this->generateStateValueReciepientPdf($data,$template,$action,$expectedDataSize);
        //dd($data);
        if ($result['success']) {
            return $result['pdf'];
        }

        return back()->with('error', $result['message']);
    }

    private function generateStateValueReciepientPdf($data, $template, $action, $expectedDataSize)
    {
        // if ($this->checkInvoiceListDataUniformity($data,$expectedDataSize)&& $this->checkIfCommuneIsNotNull()) {

        $filename = "StateValueCollector" . Str::random(8) . ".pdf";
        //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
        $pdf = PDF::loadView("exports.".$template, ['data' => $data,"commune"=> $this->commune])->setPaper('a4', 'landscape')->stream($filename);

        return ['success' => true, 'pdf' => $pdf];
        // }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
}
