<?php

namespace App\Http\Controllers;

use App\DataTables\AccountantDepositsOutrightDataTable;
use App\Traits\HandlesDateFilters;
use Illuminate\Http\Request;

class AccountantDepositOutrightController extends Controller
{
    use HandlesDateFilters;

    public function index(Request $request, AccountantDepositsOutrightDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        return $dataTable->with(
            [
                'startDate' => $this->s_date,
                'endDate' => $this->e_date,
            ]
        )->render('pages/accountant_deposits_outright.list');
    }
}
