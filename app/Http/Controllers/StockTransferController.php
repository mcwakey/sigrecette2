<?php

namespace App\Http\Controllers;

use App\DataTables\CollectorsDataTable;
use App\DataTables\StockTransfersDataTable;
use App\Models\User;
use Illuminate\Http\Request;

class StockTransferController extends Controller
{
    public function index(CollectorsDataTable $dataTable)
    {
        // return $taxablesDataTable->with('id', $taxpayer->id)
        //         ->render('pages/taxpayers.show', compact('taxpayer','taxpayerActionLog'));

                $collectors = User::select('users.id', 'users.name as user_name', 'roles.name as role_name')
                                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                                    ->where('roles.name', 'collecteur')
                                    ->get();

        return $dataTable->render('pages/stock_transfers.list', compact('collectors'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $userId, StockTransfersDataTable $dataTable)
    {
         $user = User::find($userId);
         //dd($user, $dataTable);
        //return view('pages/stock_transfers.show', compact('user'));

        // $collectors = User::select('users.id', 'users.name as user_name', 'roles.name as role_name')
        //                             ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        //                             ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        //                             ->where('roles.name', 'collecteur')
        //                             ->get();

        return $dataTable->with('id', $user->id)->render('pages/stock_transfers.show', compact('user'));
    }
}

