<?php

namespace App\Http\Controllers;

use App\DataTables\EreasDataTable;
use App\Models\Erea;
use Illuminate\Http\Request;

class EreasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(EreasDataTable $dataTable)
    {
        //return view('pages/invoices.list');
        return $dataTable->render('pages/ereas.list');
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
    public function show(Erea $erea, EreasDataTable $dataTable)
    {
        //return view('pages/invoices.show', compact('invoice'));
        //return $dataTable->render('pages/invoices.show');
        return $dataTable->render('pages/towns.show', compact('erea'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Erea $erea)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Erea $erea)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Erea $erea)
    {
        //
    }
}