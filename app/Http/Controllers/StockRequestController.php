<?php
namespace App\Http\Controllers;
use App\DataTables\StockRequestsDataTable;
use App\DataTables\StockRequestsSumDataTable;
class StockRequestController extends Controller
{
    public function index(StockRequestsSumDataTable $dataTable)
    {
        return $dataTable->render('pages/stock_requests.list');
    }
    public function show(string $reqNo, StockRequestsDataTable $dataTable)
    {
        return $dataTable->with('reqNo', $reqNo)->render('pages/stock_requests.show', ['reqNo' => $reqNo]);
    }
}
