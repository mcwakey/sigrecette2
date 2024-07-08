<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\StockRequestsSumDataTable;
use App\DataTables\StockRequestsDataTable;
use App\Models\User;

class StockRequestController extends Controller
{
    public function index(StockRequestsSumDataTable $dataTable)
    {
        return $dataTable->render('pages/stock_requests.list');
    }

    
    public function show(string $reqNo, StockRequestsDataTable $dataTable)
    {
        //  $user = User::find($userId);
         //dd($user, $dataTable);
        //return view('pages/stock_transfers.show', compact('user'));

        // $collectors = User::select('users.id', 'users.name as user_name', 'roles.name as role_name')
        //                             ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        //                             ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        //                             ->where('roles.name', 'collecteur')
        //                             ->get();

        return $dataTable->with('reqNo', $reqNo)->render('pages/stock_requests.show', compact('reqNo'));
    }
}
