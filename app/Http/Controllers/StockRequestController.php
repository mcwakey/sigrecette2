<?php
namespace App\Http\Controllers;
use App\DataTables\StockRequestsDataTable;
use App\DataTables\StockRequestsSumDataTable;
use App\Traits\HandlesDateFilters;
use Illuminate\Http\Request;

class StockRequestController extends Controller
{
    use  HandlesDateFilters;
    public function index(Request $request,StockRequestsSumDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        return $dataTable->with(
            [
                'startDate' => $this->s_date,
                'endDate' => $this->e_date,
            ]
        )->render('pages/stock_requests.list');
    }
    public function show(Request $request,string $reqNo, StockRequestsDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        return $dataTable->with(
           [
               'reqNo'=>$reqNo,
               'startDate' => $this->s_date,
               'endDate' => $this->e_date,
           ]
        )->render('pages/stock_requests.show', ['reqNo' => $reqNo]);
    }
}
