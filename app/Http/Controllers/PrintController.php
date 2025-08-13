<?php

namespace App\Http\Controllers;

use App\Actions\DownloadMultipleInvoiceAction;
use App\Actions\PrintWithData;
use App\Actions\PrintWithoutData;
use App\DataTables\PrintablesDataTable;
use App\Models\PrintFile;
use App\Models\User;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PrintController extends Controller
{

    public function index(PrintablesDataTable $printablesDataTable)
    {
        return $printablesDataTable->render('pages/printables.list');
    }
    /**
     * @param $data
     * @return RedirectResponse|Response|mixed
     */
    public function download(PrintWithoutData $actionExecute,$data = null, $type = null, $action = null, User $id = null)
    {
        return $actionExecute->execute($data,$type,$action,$id);
    }
    /**
     * @return RedirectResponse|Response|mixed
     */
    public function downloadWithPrintData(PrintWithData $actionExecute,PrintFile $printFile, $type = null, $action = null)
    {
        return $actionExecute->execute($printFile, $type, $action);
    }


    public function downloadMultipleInvoicePdf(DownloadMultipleInvoiceAction $actionExecute,Request $request, int $action = null):
    \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse
    {

        return $actionExecute->execute($action);
    }
}
