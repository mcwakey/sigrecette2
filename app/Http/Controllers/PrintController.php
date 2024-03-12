<?php

namespace App\Http\Controllers;

use App\Helpers\PdfGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrintController extends Controller
{
    public function __construct(private PdfGenerator $pdfGenerator)
    {
    }


    /**
     * @param $type
     * @return string
     */
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


    /**
     * @param $type
     * @param $data
     * @return \Illuminate\Http\RedirectResponse|Response|mixed
     */
    protected function processType($type, $data,$action)
    {
        switch ($type) {
            case 1:
                return $this->downloadReceipt($data,$type);
            case 2:
                return $this->downloadInvoicesList($data,$type,$action);
            default:
                return $this->downloadInvoice($data,$type,$action);
        }
    }

    /**
     * @param $data
     * @param null $type
     * @return \Illuminate\Http\RedirectResponse|Response|mixed
     */
    public function download( $data,$type=null,$action=null)
    {
        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }
        $data = json_decode($data, true);
        return $this->processType($type,$data,$action);



    }

    /**
     * @param $data
     * @param $type
     * @param PdfGenerator $pdfGenerator
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function downloadInvoice($data,$type,$action){
        $templateName = $this->getTemplateByType($type);
        $result = $this->pdfGenerator->generateInvoicePdf($data,$templateName,$action);
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


    /**
     * @param $data
     * @param $type
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function downloadInvoicesList($data,$type,$action)
    {
        //dd($data,$type,$action);
        $templateName = $this->getTemplateByType($type);
        $result = $this->pdfGenerator->generateInvoiceListPdf($data,$templateName,$action);
        //dd($data);
        if ($result['success']) {
            return $result['pdf'];
        }

        return back()->with('error', $result['message']);
    }


}
