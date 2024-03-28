<?php

namespace App\Http\Controllers;

use App\DataTables\YearDataTable;
use App\Models\Year;
use Illuminate\Http\Request;

class YearsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(YearDataTable $dataTable)
    {
        //return view('pages/years.list');
        return $dataTable->render('pages/years.list');
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
    public function show(Year $year,YearDataTable $yearsDataTable)
    {
        return $yearsDataTable->with('id',$year->id)->render('pages/years.show', compact('year'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Year $year)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Year $year)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Year $year)
    {
        //
    }
}


