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
        return $this->pdfGenerator->processType($type,$data,$action);



    }





}
