<?php

namespace App\Http\Controllers;

use App\DataTables\StockTransfersDataTable;
use Illuminate\Http\Request;

class StockTransferController extends Controller
{
    public function index(StockTransfersDataTable $dataTable)
    {
        return $dataTable->render('pages/stock_Transfers.list');
    }
}
