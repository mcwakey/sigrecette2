<?php

namespace App\Http\Controllers;

use App\Helpers\PdfGenerator;
use App\Traits\DispatchesMessages;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PrintController extends Controller
{
    use DispatchesMessages;
    public function __construct(private PdfGenerator $pdfGenerator)
    {
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
        $result= $this->pdfGenerator->processType($type,$data,$action);


        if ($result['success']) {
            $this->dispatchMessage("Ficher imprimable","create");
            return $result['pdf'];
        }

        $this->dispatchMessage("Ficher imprimable","create","error",$result['message']);
        return back()->with('error', $result['message']);
    }





}
