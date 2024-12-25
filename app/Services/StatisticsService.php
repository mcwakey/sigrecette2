<?php
namespace App\Services;
use App\Contracts\InvoiceStatisticsInterface;
use App\Contracts\TaxpayerStatisticsInterface;
use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStaticsEnums;
use App\Enums\InvoiceStatusEnums;
use App\Enums\PaymentStatusEnums;
use App\Enums\StatisticKeysEnums;
use App\Enums\TaxpayerStateEnums;
use App\Enums\TaxpayerStaticsEnums;
use App\Helpers\Constants;
use App\Models\Activity;
use App\Models\Canton;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxable;
use App\Models\TaxLabel;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use App\Models\Town;
use App\Models\Year;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class StatisticsService implements TaxpayerStatisticsInterface, InvoiceStatisticsInterface
{
    private Year $year;
    protected string $NoneMessage = 'Non défini';
    public function __construct(Year $year = null)
    {
        $this->year = $year ?? Year::getActiveYear();
    }
    public function getTaxpayerQuery(Year $year)
    {
        return Taxpayer::whereYear('created_at', $year->name)
            ->where('type','=',Constants::TITRE);
    }
    public function getStats(string|null $type = null): array
    {
        switch ($type) {
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
            case TaxpayerStaticsEnums::BY_LABEL:
                return $this->countTaxpayersByTaxLabel($this->year);
            default:
                return $this->getAllStatistics();
        }
    }
    public function countTaxpayers(Year $year): array
    {
        $baseQuery = $this->getTaxpayerQuery($year);
        $mobileCount = (clone $baseQuery)
            ->where('taxpayers.from_mobile_and_validate_state', '=', TaxpayerStateEnums::APPROVED)
            ->count();
        $deletedCount = (clone $baseQuery)
            ->onlyTrashed()
            ->count();
        $genderCounts = (clone $baseQuery)
            ->selectRaw('gender, count(*) as count')
            ->groupBy('gender')
            ->pluck('count', 'gender')
            ->merge(['Total' => $baseQuery->count()])
            ->toArray();
        return array_merge($genderCounts, [
            'from_mobile_and_validate' => $mobileCount,
            'deleted' => $deletedCount,
        ]);
    }
    public function countTaxpayersByCategory(Year $year): array
    {
        $categories = Category::all()->pluck('name', 'id');
        return $this->getTaxpayerQuery($year)->selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) use ($categories) {
                $categoryName = $categories[$item->category_id] ?? $this->NoneMessage;
                return ['value' => $item->count, 'category' => $categoryName];
            })
            ->toArray();
    }
    public function countTaxpayersByActivity(Year $year): array
    {
        $activities = Activity::all()->pluck('name', 'id');
        return $this->getTaxpayerQuery($year)->selectRaw('activity_id, count(*) as count')
            ->groupBy('category_id')
            ->get()
            ->map(function ($item) use ($activities) {
                $activityName = $activities[$item->activity_id] ?? $this->NoneMessage;
                return ['value' => $item->count, 'activity' => $activityName];
            })
            ->toArray();
    }
    public function countTaxpayersByCanton(Year $year): array
    {
        $cantons = Canton::all()->pluck('name', 'id');
        $counts = $this->getTaxpayerQuery($year)->selectRaw('town_id, count(*) as count')
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
    public function countTaxpayersByTown(Year $year): array
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
    public function countTaxpayersByZone(Year $year): array
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
    public function countTaxpayersState(Year $year): array
    {
        $count_valid = 0;
        $count_no_valid = 0;
        $taxpayers_without_invoices = 0;
        $taxpayers = $this->getTaxpayerQuery($year)->get();
        foreach ($taxpayers as $taxpayer) {
            if ($taxpayer->invoices->isNotEmpty()) {
                $is_valid = true;
                foreach ($taxpayer->invoices as $invoice) {
                    if ($invoice->pay_status == 'OWING' || $invoice->pay_status == 'PART PAID') {
                        $is_valid = false;
                        break;
                    }
                }
                if ($is_valid) {
                    $count_valid++;
                } else {
                    $count_no_valid++;
                }
            } else {
                $taxpayers_without_invoices += 1;
            }
        }
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
    public function countTaxpayersByTaxLabel(Year $year,string $category='CATEGORY 1'): array
    {
        $taxLabels = TaxLabel::with('taxables')->where('category', 'LIKE', "%{$category}%")->get();
        $counts = TaxpayerTaxable::whereYear('taxpayer_taxables.created_at', $year->name)
            ->selectRaw('taxables.tax_label_id, count(*) as count')
            ->join('taxables', 'taxpayer_taxables.taxable_id', '=', 'taxables.id')
            ->groupBy('taxables.tax_label_id')
            ->get()
            ->map(function ($item) use ($taxLabels) {
                $labelName = $taxLabels->firstWhere('id', $item->tax_label_id)?->name;
                return [
                    'value' => $item->count,
                    'category' => $labelName,
                ];
            })
            ->filter(function ($item) {
                return !empty($item['category']);
            })
            ->toArray();
        return array_values($counts);
    }
    public function countInvoices(Year $year): array
    {
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
            ->merge(['Total' => Invoice::whereYear('created_at', $year->name)->count()])
            ->toArray();
    }
    public function getTotalRemainingToBeCollected($startDate, $endDate): float|int
    {
        $invoices = Invoice::whereIn('status', [InvoiceStatusEnums::ACCEPTED, InvoiceStatusEnums::APPROVED, InvoiceStatusEnums::APPROVED_CANCELLATION])
            ->where('invoices.type', Constants::TITRE)
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->where('invoices.pay_status', '!=', InvoicePayStatusEnums::PAID)
            ->get();
        $totalRemaining = 0;
        foreach ($invoices as $invoice) {
            $paid = Payment::where('invoice_id', $invoice->invoice_no)
                ->where('status', PaymentStatusEnums::ACCOUNTED)
                ->sum('amount');
            $restToPay = $invoice->amount - floatval($invoice->reduce_amount) - $paid;
            $totalRemaining += max($restToPay, 0);
        }
        return $totalRemaining;
    }
    public function getTotalSoldToBeCollected($startDate, $endDate): float|int
    {
        return Invoice::whereIn('status', [
            InvoiceStatusEnums::ACCEPTED,
            InvoiceStatusEnums::PENDING,
            InvoiceStatusEnums::APPROVED,
            InvoiceStatusEnums::APPROVED_CANCELLATION,
           ])
            ->where('invoices.type', Constants::TITRE)
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
            ->sum('amount');
    }
    public function getTotalCollected($startDate, $endDate): array
    {
        $comptantTotal = Payment::where('invoice_type', Constants::INVOICE_TYPE_COMPTANT)
            ->where('status', PaymentStatusEnums::ACCOUNTED)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('amount');
        $titreTotal = Payment::where('invoice_type', Constants::TITRE)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', PaymentStatusEnums::ACCOUNTED)
            ->sum('amount');
        $count_titre = Payment::where('invoice_type', Constants::TITRE)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        return [
            'comptant_total' => $comptantTotal,
            'titre_total' => $titreTotal,
            'count_titre' => $count_titre,
        ];
    }
    public function getTaxpayerByCreatedAt($startDate, $endDate): Collection
    {


        return Taxpayer::selectRaw('
                DATE(taxpayers.created_at) as date,
                COUNT(*) as total_create,
                SUM(CASE WHEN taxpayers.updated_at > taxpayers.created_at THEN 1 ELSE 0 END) as total_update,

                created_by,
                MAX(users.name) as user_name
            ')
            ->leftJoin('users', 'users.id', '=', 'taxpayers.created_by')
            ->whereBetween('taxpayers.created_at', [$startDate, $endDate])
            ->whereNotNull('taxpayers.created_by')
            ->groupBy('date', 'created_by')
            ->orderBy('date', 'asc')
            ->get();


    }
    public function getInvoiceByCreatedAt($startDate, $endDate,string $type=Constants::INVOICE_TYPE_TITRE): Collection
    {


        return Invoice::selectRaw('
                DATE(invoices.created_at) as date,
                COUNT(*) as total_create,
                SUM(CASE WHEN invoices.updated_at > invoices.created_at THEN 1 ELSE 0 END) as total_update,
                MAX(taxpayers.name) as name
            ')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'invoices.taxpayer_id')
            ->whereBetween('invoices.created_at', [$startDate, $endDate])
           ->where('invoices.type', '=', $type)
            ->groupBy('date', 'created_by')
            ->orderBy('date', 'asc')
            ->get();


    }
    public function getPaymentByCreatedAt($startDate, $endDate,string $type=Constants::INVOICE_TYPE_COMPTANT): Collection
    {

        return Payment::selectRaw('
        DATE(payments.created_at) as date,
        COUNT(*) as total_create,
        MAX(payments.code) as most_frequent_payment_code,
        MAX(taxpayers.name) as top_taxpayer
    ')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'payments.taxpayer_id')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
            ->where('payments.invoice_type', '=', $type)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

    }

    public function getPaymentsEvolution($taxpayerId=null, $invoiceId = null)
    {
        $query = Payment::selectRaw('
            DATE(payments.created_at) as date,
            COUNT(*) as total_payments,
            SUM(payments.amount) as total_amount,
            invoices.pay_status as pay_status
        ')
            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'invoices.taxpayer_id')
            ->groupBy('date', 'invoices.pay_status')
            ->orderBy('date', 'asc');

        if($taxpayerId){
            $query->where('taxpayers.id', '=', $taxpayerId);
        }
        if ($invoiceId) {
            $query->where('invoices.id', $invoiceId);
        }

        return $query->get();
    }
    public function getPaymentStats($startDate, $endDate, $taxpayerId = null, $invoiceId = null): array
    {
        $query = Payment::selectRaw('
            DATE(payments.created_at) as date,
            COUNT(*) as total_create,
            SUM(CASE WHEN payments.updated_at > payments.created_at THEN 1 ELSE 0 END) as total_update,
            payments.invoice_id,
            MAX(payments.code) as payment_code,
            MAX(taxpayers.name) as name,
            invoices.pay_status as pay_status
        ')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'payments.taxpayer_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->whereBetween('payments.created_at', [$startDate, $endDate])
        ->whereIn('invoices.status', [InvoiceStatusEnums::ACCEPTED, InvoiceStatusEnums::APPROVED, InvoiceStatusEnums::APPROVED_CANCELLATION]);

        if ($taxpayerId) {
            $query->where('payments.taxpayer_id', $taxpayerId);
        }

        if ($invoiceId) {
            $query->where('payments.invoice_id', $invoiceId);
        }

        $query->groupBy('date', 'payments.invoice_id', 'invoices.pay_status')
            ->orderBy('date', 'asc');

        $payments = $query->get();

        $totalMonthlyPayments = Payment::whereBetween('created_at', [$startDate, $endDate])
            ->when($taxpayerId, fn($q) => $q->where('taxpayer_id', $taxpayerId))
            ->sum('amount');

        $totalOwing = Invoice::where('pay_status', InvoicePayStatusEnums::OWING)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('invoices.status', [InvoiceStatusEnums::ACCEPTED, InvoiceStatusEnums::APPROVED, InvoiceStatusEnums::APPROVED_CANCELLATION])
            ->when($taxpayerId, fn($q) => $q->where('taxpayer_id', $taxpayerId))
            ->sum('amount');

        $totalPaidCount = Invoice::where('pay_status', InvoicePayStatusEnums::PAID)
            ->whereBetween('created_at', [$startDate, $endDate])
          ->when($taxpayerId, fn($q) => $q->where('taxpayer_id', $taxpayerId))
            ->count();

        $totalInvoiceCount = Invoice::whereBetween('created_at', [$startDate, $endDate])
           ->when($taxpayerId, fn($q) => $q->where('taxpayer_id', $taxpayerId))
            ->count();

        $paidPercentage = $totalInvoiceCount > 0
            ? round(($totalPaidCount / $totalInvoiceCount) * 100, 2)
            : 0;

        return [
            'payments' => $payments,
            'totalMonthlyPayments' => $totalMonthlyPayments,
            'totalOwing' => $totalOwing,
            'paidPercentage' => $paidPercentage,
        ];
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
            StatisticKeysEnums::BY_TAXLABEL => $this->countTaxpayersByTaxLabel($this->year),
        ];
    }
}
