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
     * @param null $action
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
       // dd($type, $data);


        switch ($type) {
            case 1:
                return $this->pdfGenerator->downloadReceipt($data,'payments',$action);
            case 2:

                if($action==3){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,'invoices-registre',$action);
                }
                elseif ($action==4){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,'invoices-distribution',$action);

                }
                elseif ($action==5){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,'invoices-journal-receveur',$action);

                }
                elseif ($action==41){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,'invoices-recouvrement',$action);

                }
                elseif ($action==42){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,'invoices-recouvrement',$action);

                }
                else{
                    return $this->pdfGenerator->generateInvoiceListPdf($data,'invoices-list',$action);
                }
            //case 3:dd($data,$action,'invoices-journal-receveur');
            //case 4:return 'invoices-distribution';
            //case 5:return 'invoices-recouvrement';
            case 11: return $this->pdfGenerator->generataxpayerFormPdf($data,'taxpayer-form');
            case 6:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-account-iv-collector',$action);
            case 7:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-account-iv-receveur',$action);
            case 8:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-versement-collecteur',$action);


            case 9:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-versement-regisseur',$action);
            case 10:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'livre-journal-regie',$action);
            case 15:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-iv-regisseur',$action);
            case 16:return $this->pdfGenerator->generateStateValueCollectorPdf($data,"state-versement-regisseur-comptant",$action);


            default:
                return$this->pdfGenerator->generateInvoicePdf($data,'invoices',$action);

        }
    }



}
