<?php
namespace App\Http\Controllers;
use App\DataTables\CollectorDepositsDataTable;
use App\Models\Payment;
use Illuminate\Http\Request;
class CollectorDepositController extends Controller
{
    public function index(Request $request, CollectorDepositsDataTable $dataTable)
    {
        $validatedData = $request->validate([
            'id' => ['nullable', 'integer'],
        ]);
        $user_id = isset($validatedData['id']) ? $validatedData['id'] : null;
        return $dataTable->with('id', $user_id)->render('pages/collector_deposits.list');
    }
    public function show(Payment $payment, CollectorDepositsDataTable $dataTable)
    {
        return $dataTable->with('id', $payment->id)
            ->render('pages/collector_deposits.show', ['payment' => $payment]);
    }
}
