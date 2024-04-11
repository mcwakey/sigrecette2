<?php

namespace App\Http\Controllers;

use App\DataTables\CollectorDepositsDataTable;
use App\Models\Payment;
use Illuminate\Http\Request;

class CollectorDepositController extends Controller
{
    public function index(CollectorDepositsDataTable $dataTable)
    {
        return $dataTable->render('pages/collector_deposits.list');
    }
    
    public function show(Payment $payment, CollectorDepositsDataTable $dataTable)
    {
        // Assuming you want to pass the $taxpayer to the show view
        // return view('pages.taxpayers.show', compact('taxpayer'))
        //     ->with('dataTable', $dataTable->html());
        //return $dataTable->render('pages/taxpayers.show')-> with ('taxpayer', $taxpayer);


        // $taxpayerActionLog = UserLogs::where('taxpayer_id',$taxpayer->id)
        // ->orderBy('id', 'desc')
        // ->limit(10)
        // ->get();

        return $dataTable->with('id', $payment->id)
                ->render('pages/collector_deposits.show', compact('payment'));
    }
}
