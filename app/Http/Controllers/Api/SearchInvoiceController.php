<?php

namespace App\Http\Controllers\Api;

use App\Models\Invoice;
use App\Models\Zone;
use App\Models\Taxpayer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SearchInvoiceResource;

class SearchInvoiceController extends Controller
{
    public function search(Request $request)
    {
        $zoneName = $request->input('zone', null);

        $quer_r=Invoice::query();
        if ($zoneName) {
            $zone = Zone::where('name' ,$zoneName)->first();
          // dd($zone,$zoneName);
            if ($zone) {
                $quer_r=   $quer_r->whereHas('taxpayer', function ($query) use ($zone) {
                    $query->where('zone_id', $zone->id);
                });
            }else {
                return SearchInvoiceResource::collection(collect([]));
            }
        }

        return SearchInvoiceResource::collection($quer_r->get());
    }


}
