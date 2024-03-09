<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfGenerator
{
    /**
     * @param array $data
     * @param string $template
     * @param int|null $action
     * @return array
     */
    public function generateInvoiceListPdf(array $data,string $template,int $action=null):array
    {
        if ($this->checkInvoiceListDataUniformity($data)) {

            $filename = "Invoice-list-" . Str::random(8) . ".pdf";
            //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,'titles'=>$this->generateTitleWithAction($action)])->stream($filename);

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
            case 2:
                break;
            default:
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
        $expectedDataSize = 11;

        foreach ($data as $item) {
            if (count($item) !== $expectedDataSize) {
                return false;
            }
        }

        return true;
    }


    /**
     * @param $data
     * @return bool
     */
    public function checkInvoiceDataUniformity($data): bool
    {
        $expectedDataSize = 13;
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
                    return false;
                }
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
        if ($this->checkInvoiceDataUniformity($data)) {
            $filename="Invoice-".$data[2].'-'.Str::random(8).".pdf";

            //dd($data);
            $pdf= PDF::loadView("exports.".$templateName, ['data' => $data])
                // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
                ->stream($filename);
            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
}
