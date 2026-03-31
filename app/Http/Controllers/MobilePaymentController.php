<?php

namespace App\Http\Controllers;

use App\DataTables\MobilePaymentsDataTable;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MobilePaymentController extends Controller
{
    public function index(Request $request, MobilePaymentsDataTable $dataTable)
    {
        $validatedData = $request->validate([
            'status' => ['nullable', 'string', Rule::in(['pending', 'verifying', 'success', 'failed', 'expired'])],
        ]);

        $status = $validatedData['status'] ?? null;

        return $dataTable->with([
            'status' => $status,
        ])->render('pages.mobile-payments.list');
    }
}
