<?php

namespace App\Http\Controllers\Api;

use App\Models\Taxpayer;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\TaxpayerCreationMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\TaxpayerShowResource;
use App\Http\Resources\TaxpayerIndexResource;
use App\Http\Resources\TaxpayerStoreResource;
use App\Http\Requests\Api\StoreTaxpayerRequest;

class TaxpayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TaxpayerIndexResource::collection(Taxpayer::paginate(10));
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
        $validatedData = $request->validated();

        $email =  $validatedData['email'];
        $password = Str::random(8);
        $validatedData['password'] = Hash::make($password);

        $taxpayer = Taxpayer::create($validatedData);

        if($email){
            Mail::to($email)->send(new TaxpayerCreationMail(['data' => $taxpayer]));
        }

        return new TaxpayerStoreResource($taxpayer);
    }

    /**
     * Display the specified resource.
     */
    public function show(Taxpayer $taxpayer)
    {
        return new TaxpayerShowResource($taxpayer);
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
