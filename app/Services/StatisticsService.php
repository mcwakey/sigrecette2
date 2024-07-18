<?php

namespace App\Services;

use App\Contracts\InvoiceStatisticsInterface;
use App\Contracts\TaxpayerStatisticsInterface;
use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStaticsEnums;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Enums\StatisticKeysEnums;
use App\Enums\TaxpayerStaticsEnums;
use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxable;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Models\Year;
use App\Models\Zone;
use Carbon\Carbon;

class StatisticsService implements TaxpayerStatisticsInterface,InvoiceStatisticsInterface
{
    private Year $year;
    protected string $NoneMessage='Non défini';
    public function __construct(Year $year = null)
    {
        $this->year = $year ?? Year::getActiveYear();
    }
    public function getTaxpayerQuery (Year $year)
    {
        return Taxpayer::whereYear('created_at', $year->name);
    }

    public function getStats(string|null $type=null):array
    {
        switch ($type){
            case TaxpayerStaticsEnums::BY_GENDER:
                return $this->countTaxpayers($this->year);
            case TaxpayerStaticsEnums::BY_CATEGORY:
                return $this->countTaxpayersByCategory($this->year);
            case TaxpayerStaticsEnums::BY_ACTIVITY:
                return $this->countTaxpayersByActivity($this->year);
            case TaxpayerStaticsEnums::BY_CANTON:
                return $this->countTaxpayersByCanton($this->year);
            case TaxpayerStaticsEnums::BY_TOWN:
                return $this->countTaxpayersByTown($this->year);
            case TaxpayerStaticsEnums::BY_ZONE:
                return $this->countTaxpayersByZone($this->year);
            case TaxpayerStaticsEnums::BY_TAXABLE:
                return $this->countTaxpayersByTaxables($this->year);
            case InvoiceStaticsEnums::BY_INVOICE:
                return $this->countInvoices($this->year);
            default:
                return $this->getAllStatistics();
        }

    }

    public  function countTaxpayers(Year $year): array{
        return $this->getTaxpayerQuery($year)->selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->merge(['Total' =>  $this->getTaxpayerQuery($year)->count()])
            ->toArray();
    }

    public  function countTaxpayersByCategory(Year $year): array
    {
        $categories = Category::all()->pluck('name', 'id');

        $counts = $this->getTaxpayerQuery($year)->selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) use ($categories) {
                $categoryName = $categories[$item->category_id] ?? $this->NoneMessage;
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->toArray();

        return $counts;
    }
    public  function countTaxpayersByActivity(Year $year): array
    {
        $activities = Activity::all()->pluck('name', 'id');

        $counts =  $this->getTaxpayerQuery($year)->selectRaw('activity_id, count(*) as count')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) use ($activities) {
                $activityName = $activities[$item->activity_id] ?? $this->NoneMessage;
                return ['value' => $item->count, 'activity' => $activityName];
            })
            ->toArray();

        return $counts;
    }


    public  function countTaxpayersByCanton(Year $year): array
    {
        $cantons = Canton::all()->pluck('name', 'id');
        $counts =  $this->getTaxpayerQuery($year)->selectRaw('town_id, count(*) as count')
            ->groupBy('town_id')
            ->get()
            ->map(function ($item) use ($cantons) {
                $categoryName = $item->town ? $cantons[$item->town->canton_id] ?? $this->NoneMessage : $this->NoneMessage;
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->unique(function ($item) {
                return $item['category'];
            })
            ->toArray();

        return array_values($counts);
    }
    public  function countTaxpayersByTown(Year $year): array
    {
        $cantons = Town::all()->pluck('name', 'id');
        $counts = $this->getTaxpayerQuery($year)->selectRaw('town_id, count(*) as count')
            ->groupBy('town_id')
            ->get()
            ->map(function ($item) use ($cantons) {
                $categoryName = $item->town ? $cantons[$item->town_id] ?? $this->NoneMessage : $this->NoneMessage;
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->unique(function ($item) {
                return $item['category'];
            })
            ->toArray();

        return array_values($counts);
    }
    public  function countTaxpayersByZone(Year $year): array
    {
        $cantons = Zone::all()->pluck('name', 'id');
        $counts = $this->getTaxpayerQuery($year)->selectRaw('zone_id, count(*) as count')
            ->groupBy('town_id')
            ->get()
            ->map(function ($item) use ($cantons) {
                $categoryName = $item->zone_id ? $cantons[$item->zone_id] ?? $this->NoneMessage : $this->NoneMessage;
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->unique(function ($item) {
                return $item['category'];
            })
            ->toArray();

        return array_values($counts);
    }

    public  function countTaxpayersState(Year $year): array
    {
        $count_valid = 0;
        $count_no_valid = 0;
        $taxpayers_without_invoices=0;

        $taxpayers = $this->getTaxpayerQuery($year)->get();
        foreach ($taxpayers as $taxpayer) {
            if ($taxpayer->invoices->isNotEmpty()) {
                $is_valid = true;
                foreach ($taxpayer->invoices as $invoice) {
                    if ($invoice->pay_status == 'OWING'|| $invoice->pay_status == 'PART PAID') {
                        $is_valid = false;
                        break;
                    }
                }
                if ($is_valid) {
                    $count_valid++;
                }else {
                    $count_no_valid++;
                }
            }else{
                $taxpayers_without_invoices+=1;
            }
        }

        // Retourner les compteurs
        return [
            ['value' => $count_valid, 'category' => "A jour"],
            ['value' => $count_no_valid, 'category' => "Non à jour"],
            ['value' => $taxpayers_without_invoices, 'category' => "Sans avis"],
        ];
    }


    public function countTaxpayersByTaxables(Year $year): array
    {
        $taxables = Taxable::all()->pluck('name', 'id');
        $counts = TaxpayerTaxable::whereYear('created_at', $year->name)->selectRaw('taxable_id, count(*) as count')
            ->groupBy('taxable_id')
            ->get()
            ->map(function ($item) use ($taxables) {
                $categoryName = $taxables[$item->taxable_id];
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->unique(function ($item) {
                return $item['category'];
            })
            ->toArray();


        return array_values($counts);
    }

    public function countInvoices(Year $year):array{
        return Invoice::whereYear('created_at', $year->name)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->merge(['NOEXPIRED' => Invoice::whereYear('created_at', $year->name)
                ->where('status', '!=', 'EXPIRED')
                ->count()])
            ->merge(['Pending' => Invoice::whereYear('created_at', $year->name)
                ->where('status', '!=', 'EXPIRED')
                ->where('delivery', '=', 'NOT DELIVERED')
                ->count()])
            ->merge(['Total' => Invoice::whereYear('created_at', $year->name) ->count()])
            ->toArray();
    }
    public  function getTotalRemainingToBeCollected( $startDate, $endDate): float|int
    {
        $invoices = Invoice::whereIn('status', [InvoiceStatusEnums::APPROVED, InvoiceStatusEnums::APPROVED_CANCELLATION])
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->where('invoices.pay_status', '!=', InvoicePayStatusEnums::PAID)
            ->get();
        $totalRemaining = 0;
        foreach ($invoices as $invoice) {
            $paid = Payment::where('invoice_id', $invoice->invoice_no)
                ->where('status',PaymentStatusEnums::ACCOUNTED)
                ->sum('amount');
            $restToPay = $invoice->amount - doubleval($invoice->reduce_amount) - $paid;
            $totalRemaining += max($restToPay, 0);
        }

        return $totalRemaining;
    }
    protected function getAllStatistics(): array
    {
        return [
            StatisticKeysEnums::BY_CATEGORY => $this->countTaxpayersByCategory($this->year),
            StatisticKeysEnums::BY_ACTIVITY => $this->countTaxpayersByActivity($this->year),
            StatisticKeysEnums::BY_CANTON => $this->countTaxpayersByCanton($this->year),
            StatisticKeysEnums::BY_TOWN => $this->countTaxpayersByTown($this->year),
            StatisticKeysEnums::BY_ZONE => $this->countTaxpayersByZone($this->year),
            StatisticKeysEnums::BY_TAXABLE => $this->countTaxpayersByTaxables($this->year),
            StatisticKeysEnums::BY_STATE => $this->countTaxpayersState($this->year),
        ];
    }


}
