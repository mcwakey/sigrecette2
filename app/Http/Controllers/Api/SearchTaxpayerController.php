<?php

namespace App\Http\Controllers\Api;

use App\Models\Taxpayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SearchTaxpayerResource;

class SearchTaxpayerController extends Controller
{
    public function search(Request $request)
    {
        $taxpayerName = $request->input('name', null);
        $mobilePhone = $request->input('mobilephone', null);
        $town = $request->input('town', null);
        $zone = $request->input('zone', null);

        $query = Taxpayer::query();

        if ($taxpayerName) {
            $query->where('name', 'like', '%' . $taxpayerName . '%');
        }

        if ($zone) {
            $query->where('zone', $zone);
        }

        if ($town) {
            $query->where('zone', $town);
        }

        if ($mobilePhone) {
            $query->where('mobilephone', $mobilePhone);
        }

        return  SearchTaxpayerResource::collection($query->get());
    }
}
