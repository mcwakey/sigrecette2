<?php

namespace App\Http\Controllers;

use App\DataTables\TownsDataTable;
use App\Models\Town;
use Illuminate\Http\Request;

class TownsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TownsDataTable $dataTable)
    {
        //return view('pages/invoices.list');
        return $dataTable->render('pages/towns.list');
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
    public function show(Town $town, TownsDataTable $dataTable)
    {
        //return view('pages/invoices.show', compact('invoice'));
        //return $dataTable->render('pages/invoices.show');
        return $dataTable->render('pages/towns.show', compact('town'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Town $town)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Town $town)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Town $town)
    {
        //
    }
}
