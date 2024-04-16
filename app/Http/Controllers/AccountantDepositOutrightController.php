<?php

namespace App\Http\Controllers;

use App\DataTables\AccountantDepositsDataTable;
use App\DataTables\AccountantDepositsOutrightDataTable;
use Illuminate\Http\Request;

class AccountantDepositOutrightController extends Controller
{
    public function index(AccountantDepositsOutrightDataTable $dataTable)
    {
        return $dataTable->render('pages/accountant_deposits_outright.list');
    }
}
