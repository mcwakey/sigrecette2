<?php
namespace App\Http\Controllers;
use App\DataTables\AccountantDepositsOutrightDataTable;
class AccountantDepositOutrightController extends Controller
{
    public function index(AccountantDepositsOutrightDataTable $dataTable)
    {
        return $dataTable->render('pages/accountant_deposits_outright.list');
    }
}
