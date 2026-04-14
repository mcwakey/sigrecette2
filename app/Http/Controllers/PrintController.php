<?php

namespace App\Http\Controllers;

use App\Actions\DownloadMultipleInvoiceAction;
use App\Actions\PrintWithData;
use App\Actions\PrintWithoutData;
use App\DataTables\PrintablesDataTable;
use App\Models\Commune;
use App\Models\PrintFile;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;


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


    public function downloadMultipleInvoicePdf(DownloadMultipleInvoiceAction $actionExecute,Request $request,
                                               int $action = null):
    \Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse|View
    {

        return $actionExecute->execute($action);
    }

    public function testBordereauTemplate()
    {
        $commune = Commune::first();
        $pdfGenerator = app(\App\Contracts\PdfGeneratorInterface::class);
        $titles = $pdfGenerator->generateTitleWithAction(1);

        $pdf = Pdf::loadView('exports.invoices-list', [
            'data' => collect(),
            'titles' => $titles,
            'commune' => $commune,
            'action' => 1,
            'print' => (object) [
                'last_sequence_number' => 0,
                'total_last_sequence' => 0,
            ],
        ])
            ->setPaper('a4', 'landscape')
            ->stream('bordereau-test-' . date('Ymd_His') . '.pdf');

        return $pdf;
    }
}
