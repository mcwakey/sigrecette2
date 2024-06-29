<?php

namespace App\Http\Controllers;

use App\DataTables\PrintablesDataTable;
use App\DataTables\RecoveriesDataTable;
use App\Helpers\PdfGenerator;
use App\Models\PrintFile;
use App\Models\TaxLabel;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;


class PrintController extends Controller
{

    public function __construct(private PdfGenerator $pdfGenerator)
    {
    }

    public function index(PrintablesDataTable $printablesDataTable)
    {
        return $printablesDataTable->render('pages/printables.list',);
    }

    /**
     * @param $data
     * @param null $type
     * @param null $action
     * @return RedirectResponse|Response|mixed
     */
    public function download( $data,$type=null,$action=null,User $id=null)
    {

        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }
        $data = json_decode($data, true);
        $result= $this->processType($type,$data,$action,$id);


        if ($result['success']) {
            //$this->dispatchMessage("Ficher imprimable");
            return $result['pdf'];
        }

       // $this->dispatchMessage("Ficher imprimable","create","error",$result['message']);
        return back()->with('error', $result['message']);
    }

    /**
     * @param PrintFile $printFile
     * @param null $type
     * @param null $action
     * @return RedirectResponse|Response|mixed
     */
    public function downloadWithPrintData(PrintFile $printFile ,$type=null,$action=null)
    {

        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }
        //$data = json_decode($data, true);
        $result= $this->processType($type,$printFile,$action);


        if ($result['success']) {
            //$this->dispatchMessage("Ficher imprimable");
            session()->flash('status', 'Ficher Imprimer avec success.');
            return $result['pdf'];
        }

        // $this->dispatchMessage("Ficher imprimable","create","error",$result['message']);
        session()->flash('status', "Erreur lors de la géneration du ficher");
        return back()->with('error', $result['message']);
    }

    /**
     * @param $type
     * @param $data
     * @param $action
     * @return array
     */
    public function processType($type, $data,$action,User $user=null):array
    {
         // dd($type,$data,$action);
        switch ($type) {
            case 1:
                return $this->pdfGenerator->downloadReceipt($data,'payments',$action);
            case 2:

                if($action==3){
                    return $this->pdfGenerator->generateInvoiceRegistrePdf('invoices-registre',$action);
                }
                elseif ($action==4){
                    return $this->pdfGenerator->generateInvoiceDistribtionOrInvoiceRecouvrementPdf($data,'invoices-distribution',$action,$user);
                }
                elseif ($action==41){
                    return $this->pdfGenerator->generateInvoiceDistribtionOrInvoiceRecouvrementPdf($data,'invoices-recouvrement',$action,$user);

                }
                elseif ($action==5){
                    return $this->pdfGenerator->generateJournalInvoiceListPdf($data,'invoices-journal-receveur',$action);

                }
                elseif ($action==42){
                    return $this->pdfGenerator->generateInvoiceListPdf($data,'invoices-recouvrement',$action);
                }
                elseif ($action==77){
                    return $this->pdfGenerator->generateInvoiceTypeTwoListPdf($data,'registre-journal-des-declarations-prealables-des-usagers',$action);
                }
                elseif ($action==1|| $action==2){
                    if($data instanceof PrintFile){
                        return $this->pdfGenerator->generateBordereauListPdf('invoices-list',$action,$data);
                    }else{
                        return $this->pdfGenerator->generateBordereauListPdf('invoices-list',$action);

                    }
                }
            //case 3:dd($data,$action,'invoices-journal-receveur');
            //case 4:return 'invoices-distribution';
            //case 5:return 'invoices-recouvrement';
            case 11: return $this->pdfGenerator->generataxpayerFormPdf($data,'taxpayer-form');
            case 6:return $this->pdfGenerator->generateStateAcountIvCollectorPdf($data,'state-account-iv-collectorc',$action);
            case 7:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-account-iv-receveur',$action);
            case 8:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-versement-collecteur',$action);


            case 9:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-versement-regisseur',$action);
            case 16:return $this->pdfGenerator->generateStateValueCollectorPdf($data,"state-versement-regisseur-comptant",$action);
            case 10:return $this->pdfGenerator->generateLedgersPdf('livre-journal-regie');
            case 15:return $this->pdfGenerator->generateStateValueCollectorPdf($data,'state-iv-regisseur',$action);


            default:
                return$this->pdfGenerator->generateInvoicePdf($data,'invoices',$action);

        }

    }



}
