<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreTaxpayerRequest;
use App\Models\Taxpayer;
use Illuminate\Http\Request;

class TaxpayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taxpayers = Taxpayer::all();
        return response()->json($taxpayers, 200);
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
    public function store(StoreTaxpayerRequest $request)
    {
        $taxpayer = Taxpayer::create($request->validated());
        return response()->json($taxpayer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Taxpayer $taxpayer)
    {
        return response()->json($taxpayer, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Taxpayer $taxpayer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Taxpayer $taxpayer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Taxpayer $taxpayer)
    {
        //
    }
}
