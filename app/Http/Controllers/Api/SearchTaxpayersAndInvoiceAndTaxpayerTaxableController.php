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
use App\Http\Resources\SearchTaxableResource;
use App\Http\Resources\SearchTaxlabelResource;
use App\Models\Taxable;
use App\Models\TaxLabel;

class SearchTaxpayersAndInvoiceAndTaxpayerTaxableController extends Controller
{
    public function search(Request $request)
    {
        $zoneName = $request->input('zone', null);

        // $queryTaxpayer = Taxpayer::query();
        // $queryTaxpayerTaxable = TaxpayerTaxable::query();
        // $queryInvoice = Invoice::query();

        if ($zoneName) {

            $zone = Zone::where('name', 'like', '%' . $zoneName . '%')->first();

            if ($zone) {

                $queryTaxlabel = TaxLabel::where('category', 'CATEGORY 1');

                $queryTaxable = Taxable::join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
                                        ->where('category', 'CATEGORY 1')
                                        ->select('taxables.*');

                $queryTaxpayer = Taxpayer::where('zone_id', $zone->id);
                
                $queryTaxpayerTaxable = TaxpayerTaxable::join('taxpayers', 'taxpayer_taxables.taxpayer_id', '=', 'taxpayers.id')
                                                        ->where('taxpayers.zone_id', $zone->id)
                                                        ->select('taxpayer_taxables.*');

                // $queryTaxpayerTaxable->whereHas('taxpayer', function ($query) use ($zone) {
                //     $query->where('zone_id', $zone->id);
                // });

                $queryInvoice = Invoice::join('taxpayers', 'invoices.taxpayer_id', '=', 'taxpayers.id')
                                        ->where('taxpayers.zone_id', $zone->id)
                                        ->select('invoices.*');

                // $queryInvoice->whereHas('taxpayer', function ($query) use ($zone) {
                //     $query->where('zone_id', $zone->id);
                // });
            } 
            // else {
            //     return  [
            //         'taxpayers'=> SearchTaxpayerResource::collection(collect([])),
            //         'taxpayer_taxables' =>   SearchTaxpayerTaxableResource::collection(collect([])),
            //         'invoices' => SearchInvoiceResource::collection(collect([])),

            //         // 'taxables' =>   SearchTaxpayerTaxableResource::collection(collect([])),
            //         // 'taxlabels' =>   SearchTaxpayerTaxableResource::collection(collect([])),
            //     ];
            // }
        }

        return [
            'taxlabels'=> SearchTaxlabelResource::collection( $queryTaxlabel->get()),
            'taxables'=> SearchTaxableResource::collection( $queryTaxable->get()),

            'taxpayers'=> SearchTaxpayerResource::collection( $queryTaxpayer->get()),
            'taxpayer_taxables' => SearchTaxpayerTaxableResource::collection($queryTaxpayerTaxable->get()),
            'invoices' => SearchInvoiceResource::collection($queryInvoice->get()),
        ];
    }
}
