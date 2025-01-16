<?php
namespace App\Http\Controllers;
use App\DataTables\AccountantDepositsDataTable;
use App\DataTables\AccountantDepositsSumDataTable;
use App\Traits\HandlesDateFilters;
use Illuminate\Http\Request;

class AccountantDepositController extends Controller
{
    use  HandlesDateFilters;
    public function index(Request $request,AccountantDepositsSumDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        return $dataTable->with(
            [
                'startDate' => $this->s_date,
                'endDate' => $this->e_date,
            ]
        )->render('pages/accountant_deposits.list');
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $ref, AccountantDepositsDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        return $dataTable->with([
            'ref' => $ref,
            'startDate' => $this->s_date,
            'endDate' => $this->e_date,
        ])
            ->render('pages/accountant_deposits.show');
    }
}
