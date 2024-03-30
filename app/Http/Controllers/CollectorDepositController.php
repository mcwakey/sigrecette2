<?php

namespace App\Http\Controllers;

use App\DataTables\CollectorDepositsDataTable;
use Illuminate\Http\Request;

class CollectorDepositController extends Controller
{
    public function index(CollectorDepositsDataTable $dataTable)
    {
        return $dataTable->render('pages/collector_deposits.list');
    }
}
