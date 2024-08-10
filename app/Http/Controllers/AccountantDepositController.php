<?php

namespace App\Http\Controllers;

use App\DataTables\AccountantDepositsDataTable;
use App\DataTables\AccountantDepositsSumDataTable;
use Illuminate\Http\Request;

class AccountantDepositController extends Controller
{
    public function index(AccountantDepositsSumDataTable $dataTable)
    {
        return $dataTable->render('pages/accountant_deposits.list');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ref,AccountantDepositsDataTable $dataTable)
    {
        return $dataTable->with([
            'ref'=>$ref,
        ])
            ->render('pages/accountant_deposits.show');
    }
}
