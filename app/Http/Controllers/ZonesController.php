<?php

namespace App\Http\Controllers;

use App\DataTables\ZonesDataTable;
use App\Http\Requests\StoreZoneRequest;
use App\Http\Requests\UpdateZoneRequest;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZonesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ZonesDataTable $dataTable)
    {
        return $dataTable->render('pages/zones.list');
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
    public function store(StoreZoneRequest $request)
    {
        //
    }
    /**
     * Display the specified resource.
     */
    public function show(Zone $zone, ZonesDataTable $zonesDataTable)
    {
        return $zonesDataTable->with('id', $zone->id)->render('pages/zones.show', ['zone' => $zone]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Zone $zone)
    {
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateZoneRequest $request, Zone $zone)
    {
        //
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Zone $zone)
    {
        //
    }
}
