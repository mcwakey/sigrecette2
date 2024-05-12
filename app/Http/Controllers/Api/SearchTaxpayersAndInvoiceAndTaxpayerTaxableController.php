<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SearchTaxpayerResource;
use App\Http\Resources\SearchTaxpayerTaxableResource;
use App\Http\Resources\SearchInvoiceResource;
use App\Models\Invoice;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchTaxpayersAndInvoiceAndTaxpayerTaxableController extends Controller
{
    public function search(Request $request)
    {
        $zoneName = $request->input('zone', null);

        $queryTaxpayerTaxable = TaxpayerTaxable::query();
        $queryInvoice = Invoice::query();
        $queryTaxpayer = Taxpayer::query();

        if ($zoneName) {
            $zone = Zone::where('name', 'like', '%' . $zoneName . '%')->first();
            if ($zone) {
                $queryTaxpayerTaxable->whereHas('taxpayer', function ($query) use ($zone) {
                    $query->where('zone_id', $zone->id);
                });

                $queryInvoice->whereHas('taxpayer', function ($query) use ($zone) {
                    $query->where('zone_id', $zone->id);
                });
                $queryTaxpayer->where('zone_id', $zone->id);
            } else {
                return  [
                    'taxpayers'=> SearchTaxpayerResource::collection(collect([])),
                    'taxpayer_taxables' =>   SearchTaxpayerTaxableResource::collection(collect([])),
                    'invoices' => SearchInvoiceResource::collection(collect([])),
                ];
            }
        }

        return [
            'taxpayers'=> SearchTaxpayerResource::collection( $queryTaxpayer->get()),
            'taxpayer_taxables' => SearchTaxpayerTaxableResource::collection($queryTaxpayerTaxable->get()),
            'invoices' => SearchInvoiceResource::collection($queryInvoice->get()),
        ];
    }
}
