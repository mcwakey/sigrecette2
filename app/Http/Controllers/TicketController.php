<?php

namespace App\Http\Controllers;

use App\DataTables\TicketsDataTable;
use App\Models\Taxable;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TicketsDataTable $dataTable)
    {
        //return view('pages/taxables.list');
        return $dataTable->render('pages/tickets.list');
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
    public function show(Taxable $taxable)
    {
        // return view('pages/taxables.show', compact('taxable'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Taxable $taxable)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Taxable $taxable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Taxable $taxable)
    {
        //
    }
}
