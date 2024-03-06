<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zone;
use App\Models\Taxpayer;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class Geolocation extends Controller
{
    public function zones(){
        $zones = Zone::all();
        return View('pages/geolocation.zones',compact('zones'));
    }

    public function zoneWithTaxpayers(string $zone){
        $zone = Zone::with(['taxpayers','taxpayers.town','taxpayers.town.canton','taxpayers.erea'])->find($zone);     
        return View('pages/geolocation.zone_with_taxpayers',compact('zone'));
    }

    public function users(){
        $users = User::all();
        return View('pages/geolocation.users',compact('users'));
    }

    public function setUserGeolocation(){

    }

    public function setZoneGeolocation(){

    }
}
