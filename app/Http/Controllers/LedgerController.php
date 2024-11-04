<?php

namespace App\Http\Controllers;

use App\DataTables\LedgersDataTable;

class LedgerController extends Controller
{
    public function index(LedgersDataTable $dataTable)
    {
        return $dataTable->render('pages/ledgers.list');
    }
}
