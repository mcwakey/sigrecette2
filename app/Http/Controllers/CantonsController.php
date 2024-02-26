<?php

namespace App\Http\Controllers;

use App\DataTables\CantonsDataTable;
use App\Models\Canton;
use Illuminate\Http\Request;

class CantonsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CantonsDataTable $dataTable)
    {
        //return view('pages/cantons.list');
        return $dataTable->render('pages/cantons.list');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Canton $canton,CantonsDataTable $cantonsDataTable)
    {
        return $cantonsDataTable->with('id',$canton->id)->render('pages/cantons.show', compact('canton'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Canton $canton)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Canton $canton)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Canton $canton)
    {
        //
    }
}

