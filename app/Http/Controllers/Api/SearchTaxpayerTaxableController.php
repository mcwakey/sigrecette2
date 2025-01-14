<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Resources\SearchTaxpayerTaxableResource;
use App\Models\TaxpayerTaxable;
use App\Models\Zone;
use Illuminate\Http\Request;
class SearchTaxpayerTaxableController extends Controller
{
    public function search(Request $request)
    {
        $zoneName = $request->input('zone', null);
        $quer_r = TaxpayerTaxable::query();
        if ($zoneName) {
            $zone = Zone::where('name', 'like', '%' . $zoneName . '%')->first();
            if ($zone) {
                $quer_r = $quer_r->whereHas('taxpayer', function ($query) use ($zone) {
                    $query->where('zone_id',"=", $zone->id);
                });
            } else {
                return SearchTaxpayerTaxableResource::collection(collect([]));
            }
        }
        return SearchTaxpayerTaxableResource::collection($quer_r->get());
    }
}
