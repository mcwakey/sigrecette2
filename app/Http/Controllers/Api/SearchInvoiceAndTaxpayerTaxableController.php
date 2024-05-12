<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SearchTaxpayerTaxableResource;
use App\Http\Resources\SearchInvoiceResource;
use App\Models\Invoice;
use App\Models\TaxpayerTaxable;
use App\Models\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchInvoiceAndTaxpayerTaxableController extends Controller
{
    public function search(Request $request)
    {
        $zoneName = $request->input('zone', null);

        $queryTaxpayer = TaxpayerTaxable::query();
        $queryInvoice = Invoice::query();

        if ($zoneName) {
            $zone = Zone::where('name', 'like', '%' . $zoneName . '%')->first();
            if ($zone) {
                $queryTaxpayer->whereHas('taxpayer', function ($query) use ($zone) {
                    $query->where('zone_id', $zone->id);
                });

                $queryInvoice->whereHas('taxpayer', function ($query) use ($zone) {
                    $query->where('zone_id', $zone->id);
                });
            } else {
                return  [
                    'taxpayer_taxables' =>   SearchTaxpayerTaxableResource::collection(collect([])),
                    'invoices' => SearchInvoiceResource::collection(collect([])),
                ];
            }
        }

        return [
            'taxpayer_taxables' => SearchTaxpayerTaxableResource::collection($queryTaxpayer->get()),
            'invoices' => SearchInvoiceResource::collection($queryInvoice->get()),
        ];
    }
}
