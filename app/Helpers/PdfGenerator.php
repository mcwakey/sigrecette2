<?php

namespace App\Helpers;

use App\Models\Commune;
use App\Models\Invoice;
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
     * @param array $data
     * @param string $template
     * @param int|null $action
     * @return array
     */
    public function generateInvoiceListPdf(array $data,string $template,int $action=null):array
    {

        //dd($data);
        if ($this->checkInvoiceListDataUniformity($data)&& $this->checkIfCommuneIsNotNull()) {

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
    private function checkInvoiceListDataUniformity($data): bool
    {


        $expectedDataSize = 14;

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



        if ($this->checkInvoiceDataUniformity($data) && $this->checkIfCommuneIsNotNull()) {

            $filename="Invoice-".$data[2].'-'.Str::random(8).".pdf";

            //dd($data);
            $new_amount=0;


            if($action==2 || ( intval($data[1]) !==end($data))){
                $action=2;
                $invoice = Invoice::find( $data[1]);
            }

            if($action ==null){

                $action=1;
                $pdf= PDF::loadView("exports.".$templateName, ['data' => $data,'action'=>$action,"commune"=> $this->commune])
                    // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
                    ->stream($filename);
            }else{
                $pdf= PDF::loadView("exports.".$templateName, ['data' => $data,'action'=>$action,'invoice'=> $invoice,"commune"=> $this->commune])
                    // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
                    ->stream($filename);
            }


            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
}
