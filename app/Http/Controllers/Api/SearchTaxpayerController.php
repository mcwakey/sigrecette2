<?php

namespace App\Http\Controllers\Api;

use App\Models\Zone;
use App\Models\Taxpayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SearchInvoiceResource;

class SearchTaxpayerController extends Controller
{
    public function search(Request $request)
    {
        $taxpayerName = $request->input('name', null);
        $mobilePhone = $request->input('mobilephone', null);
        $town = $request->input('town', null);
        $zoneName = $request->input('zone', null);

        $query = Taxpayer::query();

        if ($taxpayerName) {
            $query->where('name', 'like', '%' . $taxpayerName . '%');
        }

        if ($mobilePhone) {
            $query->where('mobilephone', $mobilePhone);
        }

        if ($town) {
            $query->where('town', $town);
        }

        if ($zoneName) {
            $zone = Zone::where('name', 'like', '%' . $zoneName . '%')->first();
            if ($zone) {
                $query->where('zone_id', $zone->id);
            }
        }

        return  SearchInvoiceResource::collection($query->get());
    }
}
