<?php

namespace App\Http\Controllers;

use App\DataTables\ExportInvoicesDataTable;
use App\DataTables\ExportTaxpayersDataTable;
use App\DataTables\PrintablesDataTable;
use App\Enums\ExportTypeEnums;
use App\Helpers\Constants;
use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\TaxLabel;
use App\Models\Town;
use App\Models\Year;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExportController extends Controller
{
    //public function index( string $type,ExportTaxpayersDataTable $exportTaxpayersDataTable)
    public function index(Request $request,ExportTaxpayersDataTable $exportTaxpayersDataTable,ExportInvoicesDataTable $exportInvoicesDataTable)

    {
        $year = Year::getActiveYear()->name;
        $validatedData = $request->validate([
            's_date' => 'nullable|date_format:Y-m-d H:i:s',
            'e_date' => 'nullable|date_format:Y-m-d H:i:s',
            'export_type' => ['string', Rule::in(array_keys(Constants::EXPORT_VALIDATION_MAP))],
            'disable'=>['nullable', 'integer', Rule::in(1)],
            'state'=>['nullable', 'string', Rule::in('at')],
        ]);
        $export_type = isset($validatedData['export_type'])?Constants::EXPORT_VALIDATION_MAP[$validatedData['export_type']] : null;
        if($export_type==ExportTypeEnums::TAXPAYER){
            $disable =$validatedData['disable']??null;
            $state = $validatedData['state']??null;
            $startDate = $validatedData['s_date'] ?? null;
            $endDate = $validatedData['e_date'] ?? null;
            $zones = Zone::all();
            $categories = Category::all();
            $towns = Town::all();
            $cantons = Canton::all();
            $activities = Activity::all();
            return $exportTaxpayersDataTable->with(
                [
                    'state'=>$state,
                    'disable' => $disable,
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.taxpayers.list',compact('zones','categories','towns','cantons','activities'));
        }elseif ($export_type==ExportTypeEnums::INVOICE){
            $startDate = $validatedData['s_date'] ??  Carbon::parse("{$year}-01-01 00:00:00");
            $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
            $zones = Zone::all();
            $tax_labels = TaxLabel::all();
            return $exportInvoicesDataTable->with(

                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                ]
            )->render('pages/export.invoices.list', compact('zones', 'tax_labels'));


        }

    }
}
