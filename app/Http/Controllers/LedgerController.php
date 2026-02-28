<?php

namespace App\Http\Controllers;

use App\DataTables\LedgersDataTable;
use App\Traits\HandlesDateFilters;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    use HandlesDateFilters;

    public function index(Request $request, LedgersDataTable $dataTable)
    {
        $this->handleDateFilters($request);

        return $dataTable->with(
            [
                'startDate' => $this->s_date,
                'endDate' => $this->e_date,
            ]
        )->render('pages/ledgers.list');
    }
}
