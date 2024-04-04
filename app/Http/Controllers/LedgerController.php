<?php

namespace App\Http\Controllers;

use App\DataTables\LedgersDataTable;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(LedgersDataTable $dataTable)
    {
        return $dataTable->render('pages/ledgers.list');
    }
}
