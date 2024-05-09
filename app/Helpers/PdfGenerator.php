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
use App\Models\User;
use App\Models\Year;
use Carbon\Carbon;
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

        //dd($this->commune->getImageUrlAttribute());
        $pdf = PDF::loadView("exports.".$template, ['data' => $data,"commune"=> $this->commune,'logo_url'=>$this->commune->getImageUrlAttribute()])->setPaper('a4', 'landscape')->stream($filename);

        return ['success' => true, 'pdf' => $pdf];
        // }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }

    public function generateBordereauListPdf( string $templateName, $action,PrintFile|null $printFile=null)
    {
        $type = null;
        if($action==1){
            $type=PrintNameEnums::BORDEREAU;
        }elseif ($action==2){
            $type=PrintNameEnums::BORDEREAU_REDUCTION;
        }

        if( $type!=null&&$printFile==null){
            $data=Invoice::getPrintData([InvoiceStatusEnums::PENDING],$type);
            //dd($data);
           // dd($templateName,$type,$data);
            //dd($printFile,$type,$data);
            if(count($data)>0){
                $total=0;
                foreach ($data as $datum){
                    if($type==PrintNameEnums::BORDEREAU){
                        $total+=$datum->amount;
                    }else{
                        $total+=$datum->reduce_amount;
                    }
                }
                $printFile= PrintFile::createPrintFile($type,$data,$total);
            }

        }
       // $data= Invoice::where("print_data",$printFile?->id)->get();


        if($printFile!=null&&$this->checkIfCommuneIsNotNull()){
            $data = $printFile->invoices()->get();
            //
            if ( count($data)>0) {
                $filename = $type."-" . Str::random(8) . ".pdf";
                //dd($data);
                $pdf = PDF::loadView("exports.".$templateName, ['data' => $data,'titles'=>$this->generateTitleWithAction($action),"commune"=> $this->commune,"action"=>$action,'print'=>$printFile])->setPaper('a4', 'landscape')->stream($filename);

                return ['success' => true, 'pdf' => $pdf];
            }
        }



        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    /**
     * @param array $data
     * @param string $template
     * @param int|null $action
     * @return array
     */
    public function generateJournalInvoiceListPdf(array $data,string $template,int $action=null):array
    {


        $data=Invoice::getPrintData(
            [InvoiceStatusEnums::CANCELED,
                InvoiceStatusEnums::REDUCED,
                InvoiceStatusEnums::APPROVED,
                InvoiceStatusEnums::APPROVED_CANCELLATION]);

        if ($this->checkIfCommuneIsNotNull()&& count($data)>0) {

            $filename = "Journal_des_avis_des_sommes_à_payer_confiés_par_le_receveur" .".pdf";
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,'titles'=>$this->generateTitleWithAction($action),"commune"=> $this->commune,"action"=>$action])->setPaper('a4', 'landscape')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }

    /**
     * @param string $template
     * @param int|null $action
     * @return array
     */
    public function generateInvoiceRegistrePdf(string $template,int $action=null):array
    {

        //dd($data,$template,$action);
        $data=Invoice::getPrintData(
            [InvoiceStatusEnums::CANCELED,
                InvoiceStatusEnums::REDUCED,
                InvoiceStatusEnums::APPROVED,
                InvoiceStatusEnums::APPROVED_CANCELLATION]);
        if ($this->checkIfCommuneIsNotNull()&& count($data)>0) {

            $filename = "Registre-journal-des-avis-distribués" . Str::random(8) . ".pdf";
            //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,'titles'=>$this->generateTitleWithAction($action),"commune"=> $this->commune,"action"=>$action])->setPaper('a4', 'landscape')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }

    public function generateInvoiceDistribtionOrInvoiceRecouvrementPdf(array|PrintFile $data,string $template,int $action=null,User $user=null): array
    {
        $type = null;
        if($action==4){
            $type=PrintNameEnums::FICHE_DE_DISTRIBUTION_DES_AVIS;
        }elseif ($action==41){
            $type=PrintNameEnums::FICHE_DE_RECOUVREMENT_DES_AVIS_DISTRIBUES;
        }
        if($data instanceof PrintFile){
            $printFile =$data;
            $data = $data->invoices()->get();
        }else{
            if($type!=null &&$user instanceof User){

                $data=Invoice::filterByType(Invoice::retrieveByUUIDs($data),$type);
                if (count($data)>0) {
                    $printFile= PrintFile::createPrintFile($type,$data,0,$user);

                    if($type==PrintNameEnums::FICHE_DE_DISTRIBUTION_DES_AVIS){
                        DB::transaction(function () use ($data) {
                            foreach ($data as $item){
                                $item->ondistributionprint= true;
                                $item->save();
                            }
                        });
                    }
                    else{
                        DB::transaction(function () use ($data) {
                            foreach ($data as $item){
                                $item->onrecoveryprint= true;
                                $item->save();
                            }
                        });
                    }
                }
            }else{
                $data=[];
            }

        }

        if($this->checkIfCommuneIsNotNull() &&  isset($printFile)&& count($data)>0){
                $filename = $type."-" . ".pdf";
                $pdf = PDF::loadView("exports.".$template, ['data' => $data,'titles'=>$this->generateTitleWithAction($action),"commune"=> $this->commune,"action"=>$action,'print'=>$printFile,'agent'=>$user])->setPaper('a4', 'landscape')->stream($filename);
                return ['success' => true, 'pdf' => $pdf];
        }
        return ['success' => false, 'message' => 'Invalid data structure.'];
    }
    /**
     * @param array $data
     * @param string $template
     * @param int|null $action
     * @return array
     */
    public function generateInvoiceTypeTwoListPdf(array $data,string $template,int $action=null):array
    {

        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");

            $data=Invoice::whereBetween('created_at', [$startOfYear, $endOfYear])
            ->where('type','=',Constants::INVOICE_TYPE_COMPTANT)
            ->where('status','=',InvoiceStatusEnums::APPROVED)->get();
        if ($this->checkIfCommuneIsNotNull()&& count($data)>0) {
            $filename = "Invoice-list-" . Str::random(8) . ".pdf";
            //$pdf = PDF::loadView("exports.".$template, ['data' => $data])->setPaper('a4', 'landscape')->stream($filename);
            $pdf = PDF::loadView("exports.".$template, ['data' => $data,'titles'=>$this->generateTitleWithAction($action),"commune"=> $this->commune,"action"=>$action])->setPaper('a4', 'landscape')->stream($filename);

            return ['success' => true, 'pdf' => $pdf];
        }

        return ['success' => false, 'message' => 'Invalid data structure.'];
    }

}
