<?php

namespace App\Http\Controllers;

use App\Helpers\PdfGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;


class PrintController extends Controller
{

    public function __construct(private PdfGenerator $pdfGenerator)
    {
    }





    /**
     * @param $data
     * @param null $type
     * @return RedirectResponse|Response|mixed
     */
    public function download( $data,$type=null,$action=null)
    {

        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }
        $data = json_decode($data, true);
        $result= $this->processType($type,$data,$action);


        if ($result['success']) {
            //$this->dispatchMessage("Ficher imprimable");
            return $result['pdf'];
        }

       // $this->dispatchMessage("Ficher imprimable","create","error",$result['message']);
        return back()->with('error', $result['message']);
    }

    /**
     * @param $type
     * @param $data
     * @param $action
     * @return array
     */
    public function processType($type, $data,$action):array
    {
        //dd($type, $data);

        switch ($type) {
            case 1:
                return $this->pdfGenerator->downloadReceipt($data,$action,'payments');
            case 2:

                if($action==3){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,$action,'invoices-registre');
                }
                elseif ($action==4){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,$action,'invoices-distribution');

                }
                elseif ($action==5){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,$action,'invoices-journal-receveur');

                }
                elseif ($action==41){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,$action,'invoices-recouvrement');

                }
                else{
                    return $this->pdfGenerator->generateInvoiceListPdf($data,$action,'invoices-list');
                }
            case 3:
                dd($data,$action,'invoices-journal-receveur');
            //case 4:return 'invoices-distribution';
            //case 5:return 'invoices-recouvrement';
            case 11: return $this->pdfGenerator->generataxpayerFormPdf($data,'taxpayer-form');
            case 6:return $this->pdfGenerator->generateStateValueCollectorPdf($data,$action,'state-account-iv-collector');
            case 7:return $this->pdfGenerator->generateStateValueCollectorPdf($data,$action,'state-account-iv-receveur');
            case 8:return $this->pdfGenerator->generateStateValueCollectorPdf($data,$action,'state-versement-collecteur');
            case 9:return $this->pdfGenerator->generateStateValueCollectorPdf($data,$action,'state-versement-regisseur');
            case 10:return $this->pdfGenerator->generateStateValueCollectorPdf($data,$action,'livre-journal-regie');

            default:
                return$this->pdfGenerator->generateInvoicePdf($data,$action,'invoices');

        }
    }



}
