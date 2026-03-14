<?php

namespace App\Http\Controllers;

use App\DataTables\CollectorDepositsDataTable;
use App\Models\Payment;
use App\Traits\HandlesDateFilters;
use Illuminate\Http\Request;

class CollectorDepositController extends Controller
{
    use HandlesDateFilters;

    public function index(Request $request, CollectorDepositsDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        $validatedData = $request->validate([
            'id' => ['nullable', 'integer'],
        ]);
        $user_id = isset($validatedData['id']) ? $validatedData['id'] : null;
        return $dataTable->with(
            [
                'id' => $user_id,
                'startDate' => $this->s_date,
                'endDate' => $this->e_date,
            ]
        )->render('pages/collector_deposits.list');
    }
    public function show(Request $request, Payment $payment, CollectorcDepositsDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        return $dataTable->with(
            [
                'id' => $payment->id,
                'startDate' => $this->s_date,
                'endDate' => $this->e_date,
            ]
        )
            ->render('pages/collector_deposits.show', ['payment' => $payment]);
    }
}
