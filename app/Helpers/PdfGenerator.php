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
     * @return array
     */
    public function generateInvoiceListPdf(array $data,string $template):array
    {
        if ($this->checkInvoiceListDataUniformity($data)) {
            $filename = "Invoice-list" . Str::random(8) . ".pdf";
            $pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
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
    public function generateInvoicePdf(array $data,string $templateName ):array
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
