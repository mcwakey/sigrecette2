<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\StockRequestsDataTable;

class StockRequestController extends Controller
{
    public function index(StockRequestsDataTable $dataTable)
    {
        return $dataTable->render('pages/stock_requests.list');
    }
}
