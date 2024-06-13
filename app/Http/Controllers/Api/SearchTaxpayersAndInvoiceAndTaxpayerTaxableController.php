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
use App\Http\Resources\SearchActivityResource;
use App\Http\Resources\SearchCategoryResource;
use App\Http\Resources\SearchEreaResource;
use App\Http\Resources\SearchGenderResource;
use App\Http\Resources\SearchIdTypeResource;
use App\Http\Resources\SearchPaymentResource;
use App\Http\Resources\SearchTaxableResource;
use App\Http\Resources\SearchTaxlabelResource;
use App\Http\Resources\SearchTownResource;
use App\Http\Resources\SearchZoneResource;
use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\Erea;
use App\Models\Gender;
use App\Models\IdType;
use App\Models\Payment;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Town;

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

                $queryZones = Zone::select('zones.*');

                $queryActivities = Activity::select('activities.*');

                $queryCategories = Category::select('categories.*');

                $queryEreas = Canton::select('cantons.*');

                $queryTowns = Town::select('towns.*');

                $queryGenders = Gender::select('genders.*');

                $queryIdTypes = IdType::select('id_types.*');

                $queryTaxlabels = TaxLabel::where('category', 'CATEGORY 1');

                $queryTaxables = Taxable::join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
                                        ->where('category', 'CATEGORY 1')
                                        ->select('taxables.*');

                $queryTaxpayers = Taxpayer::where('zone_id', $zone->id);
                
                $queryTaxpayerTaxables = TaxpayerTaxable::join('taxpayers', 'taxpayer_taxables.taxpayer_id', '=', 'taxpayers.id')
                                                        ->where('taxpayers.zone_id', $zone->id)
                                                        ->select('taxpayer_taxables.*');

                // $queryTaxpayerTaxable->whereHas('taxpayer', function ($query) use ($zone) {
                //     $query->where('zone_id', $zone->id);
                // });

                $queryInvoices = Invoice::join('taxpayers', 'invoices.taxpayer_id', '=', 'taxpayers.id')
                                        ->where('taxpayers.zone_id', $zone->id)
                                        ->select('invoices.*');

                $queryPayments = Payment::join('taxpayers', 'payments.taxpayer_id', '=', 'taxpayers.id')
                                        ->where('taxpayers.zone_id', $zone->id)
                                        ->select('payments.*');

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
            'zones'=> SearchZoneResource::collection( $queryZones->get()),
            'activities'=> SearchActivityResource::collection( $queryActivities->get()),
            'categories'=> SearchCategoryResource::collection( $queryCategories->get()),
            'ereas'=> SearchEreaResource::collection( $queryEreas->get()),
            'towns'=> SearchTownResource::collection( $queryTowns->get()),
            'genders'=> SearchGenderResource::collection( $queryGenders->get()),
            'id_types'=> SearchIdTypeResource::collection( $queryIdTypes->get()),

            'taxlabels'=> SearchTaxlabelResource::collection( $queryTaxlabels->get()),
            'taxables'=> SearchTaxableResource::collection( $queryTaxables->get()),

            'taxpayers'=> SearchTaxpayerResource::collection( $queryTaxpayers->get()),
            'taxpayer_taxables' => SearchTaxpayerTaxableResource::collection($queryTaxpayerTaxables->get()),
            'invoices' => SearchInvoiceResource::collection($queryInvoices->get()),

            'payments' => SearchPaymentResource::collection($queryPayments->get()),
        ];
    }
}
