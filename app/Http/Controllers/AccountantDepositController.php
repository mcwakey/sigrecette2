<?php

namespace App\Http\Controllers;

use App\DataTables\AccountantDepositsDataTable;
use Illuminate\Http\Request;

class AccountantDepositController extends Controller
{
    public function index(AccountantDepositsDataTable $dataTable)
    {
        return $dataTable->render('pages/accountant_deposits.list');
    }
}
