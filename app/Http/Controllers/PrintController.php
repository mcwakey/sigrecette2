<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrintController extends Controller
{
    protected function getTemplateByType($type)
    {
        switch ($type) {
            case 1:
                return 'payments';
            case 2:
                return 'invoices-list';
            default:
                return 'invoices';
        }
    }

    protected function processType($type, $data)
    {
        switch ($type) {
            case 1:
                return $this->downloadReceipt($data,$type);
            case 2:
                return $this->downloadInvoicesList($data,$type);
            default:
                return $this->downloadInvoice($data,$type);
        }
    }

    public function download( $data,$type=null)
    {
        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }
        $data = json_decode($data, true);
        return $this->processType($type,$data);



    }

    public function downloadInvoice($data,$type){
        $templateName = $this->getTemplateByType($type);

        if ($this->checkInvoiceDataUniformity($data) && $templateName !== null){
            $filename="Invoice-".$data[2].'-'.Str::random(8).".pdf";

            //dd($data);
            $pdf= PDF::loadView("exports.".$templateName, ['data' => $data])
                // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
                ->stream($filename);
            return $pdf;
        }
        return back();
    }
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
    public function downloadReceipt( $data, $type)
    {


        $data = json_decode($data, true);

            $filename="receipt-".$data[2].'-'.Str::random(8).".pdf";

            //dd($data);
            $pdf= PDF::loadView('exports.payments', ['data' => $data])
                // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
                ->stream($filename);
            return $pdf;

    }

    private function downloadInvoicesList($data, $type)
    {
       // dd($data);
        $templateName = $this->getTemplateByType($type);
        $filename="Invoice-".Str::random(8).".pdf";

        $pdf= PDF::loadView("exports.".$templateName, ['data' => $data])->setPaper('a4', 'landscape')
            // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
            ->stream($filename);
        return $pdf;

    }


}
