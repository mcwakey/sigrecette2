<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GenerateInvoiceController extends Controller
{

    public function download( $data)
    {
        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }

        $data = json_decode($data, true);
       if ($this->checkIfDataUniformity($data)){
           $filename="Invoice-".$data[2].'-'.Str::random(8).".pdf";

            //dd($data);
            $pdf= PDF::loadView('exports.invoices', ['data' => $data])
               // ->save(Storage::path('exports') . DIRECTORY_SEPARATOR . $filename)
                ->stream($filename);
            return $pdf;
       }

        return back();
    }
    public function checkIfDataUniformity( $data):bool
    {

        $n_0_l ="";
        if (count($data[12])>1){
            foreach ($data[12] as $index => $item)
            {
                if($index==0) $n_0_l = $item[1];
                if($n_0_l!==$item[1]){
                    return false;
                }
            }
        }
        return true;

    }
    public function downloadMultiple( $data)
    {
        if (Storage::missing("exports")) {
            Storage::makeDirectory("exports");
        }
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

}
