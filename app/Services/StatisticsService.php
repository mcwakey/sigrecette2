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
use App\Models\Budget;
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
    public function __construct(private $startDate, private $endDate, protected string $NoneMessage = 'Non défini')
    {
    }
    public function getTaxpayerQuery($dateFilter = true)
    {
        $query = Taxpayer::where('type', '=', Constants::TITRE)
            ->where(function ($q) {
                $q->whereNotIn('taxpayers.from_mobile_and_validate_state', [
                    TaxpayerStateEnums::REJECTED,
                    TaxpayerStateEnums::PENDING
                ])->orWhereNull('taxpayers.from_mobile_and_validate_state');
            });

        if ($dateFilter) {
            $query->where(function ($q) {
                $q->whereBetween('created_at', [$this->startDate, $this->endDate])
                    ->orWhereBetween('updated_at', [$this->startDate, $this->endDate]);
            });
        }

        return $query;
    }



    public function countTaxpayers(): array
    {
        $baseQuery = $this->getTaxpayerQuery(false);
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
    public function countTaxpayersByCategory(): array
    {
        $categories = Category::pluck('name', 'id');

        return $this->getTaxpayerQuery(false)
            ->selectRaw('category_id, count(*) as count')
            ->groupBy('category_id')
            ->get()
            ->map(fn($item) => [
                'value' => $item->count,
                'category' => $categories[$item->category_id] ?? $this->NoneMessage
            ])
            ->toArray();
    }

    public function countTaxpayersByActivity(): array
    {
        $activities = Activity::pluck('name', 'id');

        return $this->getTaxpayerQuery(false)
            ->selectRaw('activity_id, count(*) as count')
            ->groupBy('activity_id')
            ->get()
            ->map(fn($item) => [
                'value' => $item->count,
                'activity' => $activities[$item->activity_id] ?? $this->NoneMessage
            ])
            ->toArray();
    }

    public function countTaxpayersByCanton(): array
    {
        $cantons = Canton::all()->pluck('name', 'id');
        $counts = $this->getTaxpayerQuery(false)
            ->selectRaw('towns.canton_id, count(*) as count')
            ->join('towns', 'taxpayers.town_id', '=', 'towns.id')
            ->groupBy('towns.canton_id')
            ->get()
            ->map(function ($item) use ($cantons) {
                return ['value' => $item->count, 'category' => $cantons[$item->canton_id] ?? 'Inconnu'];
            })
            ->toArray();
        return array_values($counts);
    }
    public function countTaxpayersByTown(): array
    {
        $towns = Town::all()->pluck('name', 'id');

        $counts = $this->getTaxpayerQuery(false)
            ->selectRaw('towns.id as town_id, count(*) as count')
            ->join('towns', 'taxpayers.town_id', '=', 'towns.id')
            ->groupBy('towns.id')
            ->get()
            ->map(function ($item) use ($towns) {
                return [
                    'value' => $item->count,
                    'category' => $towns[$item->town_id] ?? $this->NoneMessage
                ];
            })
            ->toArray();

        return array_values($counts);
    }

    public function countTaxpayersByZone(): array
    {
        $zones = Zone::all()->pluck('name', 'id');

        $counts = $this->getTaxpayerQuery(false)
            ->selectRaw('zones.id as zone_id, count(*) as count')
            ->join('zones', 'taxpayers.zone_id', '=', 'zones.id')
            ->groupBy('zones.id')
            ->get()
            ->map(function ($item) use ($zones) {
                return [
                    'value' => $item->count,
                    'category' => $zones[$item->zone_id] ?? $this->NoneMessage
                ];
            })
            ->toArray();

        return array_values($counts);
    }

    public function countTaxpayersState(): array
    {
        $count_valid = 0;
        $count_no_valid = 0;
        $taxpayers_without_invoices = 0;
        $taxpayers = $this->getTaxpayerQuery()->get();
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
    public function countTaxpayersByTaxables(): array
    {
        $taxables = Taxable::pluck('name', 'id');

        $counts = TaxpayerTaxable::query()
            ->join('taxpayers', 'taxpayer_taxables.taxpayer_id', '=', 'taxpayers.id')
            ->whereBetween('taxpayer_taxables.created_at', [$this->startDate, $this->endDate])
            ->where('taxpayers.type', Constants::TITRE)
            ->selectRaw('taxpayer_taxables.taxable_id, count(*) as count')
            ->groupBy('taxpayer_taxables.taxable_id')
            ->get()
            ->map(function ($item) use ($taxables) {
                return [
                    'value' => $item->count,
                    'category' => $taxables[$item->taxable_id] ?? $this->NoneMessage
                ];
            })
            ->toArray();

        return array_values($counts);
    }

    public function countTaxpayersByTaxLabel(string $category = 'CATEGORY 1'): array
    {
        $taxLabels = TaxLabel::with('taxables')->where('category', 'LIKE', "%{$category}%")->get();
        $counts = TaxpayerTaxable::with([ 'taxpayer',])->whereBetween('taxpayer_taxables.created_at', [$this->startDate, $this->endDate])
            ->whereHas('taxpayer', function ($query) {
                $query->where('type', Constants::TITRE);
            })
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
    public function countInvoices(string $type = Constants::INVOICE_TYPE_TITRE): array
    {
        return Invoice::whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->merge(['NOEXPIRED' => Invoice::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('invoices.type', '=', $type)
                ->where('status', '!=', 'EXPIRED')
                ->count()])
            ->merge(['Pending' => Invoice::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('invoices.type', '=', $type)
                ->where('status', '!=', 'EXPIRED')
                ->where('delivery', '=', 'NOT DELIVERED')
                ->count()])
            ->merge(['Total' => Invoice::whereBetween('created_at', [$this->startDate, $this->endDate])->where('invoices.type', '=', $type)
                ->count()])
            ->toArray();
    }
    public function getTotalRemainingToBeCollected(string $type = Constants::INVOICE_TYPE_TITRE): float|int
    {
        $invoices = Invoice::whereIn('status', [InvoiceStatusEnums::ACCEPTED, InvoiceStatusEnums::APPROVED, InvoiceStatusEnums::APPROVED_CANCELLATION])
            ->where('invoices.type', '=', $type)
            ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
            ->where('invoices.pay_status', '!=', InvoicePayStatusEnums::PAID)
            ->get();
        $totalRemaining = 0;
        foreach ($invoices as $invoice) {
            $paid = Payment::where('invoice_id', $invoice->invoice_no)
                ->where('status', '=', PaymentStatusEnums::ACCOUNTED)
                ->sum('amount');
            $restToPay = $invoice->amount - floatval($invoice->reduce_amount) - $paid;
            $totalRemaining += max($restToPay, 0);
        }
        return $totalRemaining;
    }
    public function getTotalSoldToBeCollected(string $type = Constants::INVOICE_TYPE_TITRE): float|int
    {
        return Invoice::whereIn('status', [
            InvoiceStatusEnums::ACCEPTED,
            InvoiceStatusEnums::PENDING,
            InvoiceStatusEnums::APPROVED,
            InvoiceStatusEnums::APPROVED_CANCELLATION,
           ])
            ->where('invoices.type', $type)
            ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
            ->sum('amount');
    }
    public function getTotalCollected(): array
    {
        $comptantTotal = Payment::where('invoice_type', '=', Constants::INVOICE_TYPE_COMPTANT)
            ->where('status', PaymentStatusEnums::ACCOUNTED)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->sum('amount');
        $titreTotal = Payment::where('invoice_type', Constants::TITRE)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', PaymentStatusEnums::ACCOUNTED)
            ->sum('amount');
        $count_titre = Payment::where('invoice_type', Constants::TITRE)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
        $count_comptant = Payment::where('invoice_type', Constants::INVOICE_TYPE_COMPTANT)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();
        return [
            'comptant_total' => $comptantTotal,
            'titre_total' => $titreTotal,
            'count_titre' => $count_titre,
            'count_comptant' => $count_comptant,
        ];
    }
    public function getTaxpayerByCreatedAt(): Collection
    {


        return Taxpayer::selectRaw('
                DATE(taxpayers.created_at) as date,
                COUNT(*) as total_create,
                SUM(CASE WHEN taxpayers.updated_at > taxpayers.created_at THEN 1 ELSE 0 END) as total_update,

                created_by,
                MAX(users.name) as user_name
            ')

            ->leftJoin('users', 'users.id', '=', 'taxpayers.created_by')
            ->where('type', '=', Constants::TITRE)
            ->whereBetween('taxpayers.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('taxpayers.created_by')
            ->groupBy('date', 'created_by')
            ->orderBy('date', 'asc')
            ->get();
    }
    public function getInvoiceByCreatedAt(string $type = Constants::INVOICE_TYPE_TITRE): Collection
    {


        return Invoice::selectRaw('
            DATE(invoices.created_at) as date,
            COUNT(*) as total_create,
            SUM(CASE WHEN invoices.updated_at > invoices.created_at THEN 1 ELSE 0 END) as total_update,
            GROUP_CONCAT(taxpayers.name SEPARATOR \', \') as name,
            MAX(invoices.pay_status) as status
')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'invoices.taxpayer_id')
            ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
            ->where('invoices.type', '=', $type)
            ->groupBy('date', 'created_by')
            ->orderBy('date', 'asc')
            ->get();
    }
    public function getPaymentByCreatedAt(string $type = Constants::INVOICE_TYPE_COMPTANT): Collection
    {

        return Payment::selectRaw('
        DATE(payments.created_at) as date,
        COUNT(*) as total_create,
        MAX(payments.code) as most_frequent_payment_code,
        MAX(taxpayers.name) as top_taxpayer
    ')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'payments.taxpayer_id')
            ->whereBetween('payments.created_at', [$this->startDate, $this->endDate])
            ->where('payments.invoice_type', '=', $type)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    public function getPaymentsEvolution($taxpayerId = null, $invoiceId = null, string $type = Constants::INVOICE_TYPE_TITRE)
    {
       // dd($startDate,$endDate,$taxpayerId);
        $query = Payment::selectRaw('
            DATE(payments.created_at) as date,
            COUNT(*) as total_payments,
            SUM(payments.amount) as total_amount,
            MAX(payments.code) as payment_code,
            invoices.pay_status as pay_status
        ')
            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'invoices.taxpayer_id')
            ->groupBy('date', 'invoices.pay_status')
            ->orderBy('date', 'asc')
            ->whereBetween('payments.created_at', [$this->startDate, $this->endDate])
            ->where('payments.invoice_type', '=', $type)
        ;

        if ($taxpayerId) {
            $query->where('taxpayers.id', '=', $taxpayerId);
        }
        if ($invoiceId) {
            $query->where('invoices.id', $invoiceId);
        }

        return $query->get();
    }
    public function getPaymentStats($taxpayerId = null, $invoiceId = null): array
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
            ->whereBetween('payments.created_at', [$this->startDate, $this->endDate])
        ->whereIn(
            'invoices.status',
            [InvoiceStatusEnums::ACCEPTED, InvoiceStatusEnums::APPROVED, InvoiceStatusEnums::APPROVED_CANCELLATION]
        );

        if ($taxpayerId) {
            $query->where('payments.taxpayer_id', $taxpayerId);
        }

        if ($invoiceId) {
            $query->where('payments.invoice_id', $invoiceId);
        }

        $query->groupBy('date', 'payments.invoice_id', 'invoices.pay_status')->orderBy('date', 'asc');

        $payments = $query->get();

        $totalMonthlyPayments = Payment::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($taxpayerId, fn($q) => $q->where('taxpayer_id', $taxpayerId))
            ->sum('amount');

        $totalOwing = Invoice::where('pay_status', InvoicePayStatusEnums::OWING)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->whereIn('invoices.status', [InvoiceStatusEnums::ACCEPTED, InvoiceStatusEnums::APPROVED, InvoiceStatusEnums::APPROVED_CANCELLATION])
            ->when($taxpayerId, fn($q) => $q->where('taxpayer_id', $taxpayerId))
            ->sum('amount');

        $totalPaidCount = Invoice::where('pay_status', InvoicePayStatusEnums::PAID)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
          ->when($taxpayerId, fn($q) => $q->where('taxpayer_id', $taxpayerId))
            ->count();

        $totalInvoiceCount = Invoice::whereBetween('created_at', [$this->startDate, $this->endDate])
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

    /**
     * Récupère les statistiques de budgets et paiements pour une année spécifique.
     *
     * @param Year $year
     * @return array
     */
    public function getStatisticsByYear(Year $year): array
    {
        $budgets = Budget::with('tax_label')
            ->where('year_id', $year->id)
            ->get();

        $data = [];
        foreach ($budgets as $budget) {
            $totalPayments = Payment::where('code', $budget->tax_label->code)
                ->whereBetween('payments.created_at', [
                    Carbon::parse("{$year->name}-01-01 00:00:00") ,
                    Carbon::parse("{$year->name}-12-31 23:59:59")])
                ->sum('amount');

            $data[] = [
                'tax_label' => $budget->tax_label->name,
                'expected_amount' => $budget->expected_amount,
                'actual_amount' => $totalPayments,
                'difference' => $budget->expected_amount - $totalPayments,
            ];
        }

        //dd($data);
        return $data;
    }
    public function c_capacity_data()
    {
        $categorieName = 'CATEGORY 1';
        $taxpayers = $this->getTaxpayersWithInvoices();
        $labels = $this->getActiveLabels($categorieName);
        $taxables = $this->getActiveTaxables();


        if (count($taxpayers) < 50) {
            $taxpayers = Taxpayer::whereHas('invoices', function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('type', '=', Constants::TITRE);
            })->get();
        }
        $taxpayer_count = count($taxpayers);
        $invoice_count = $taxpayers->sum(fn($taxpayer) =>
        $taxpayer->invoices->filter(fn($invoice) =>
            $invoice->created_at->between($this->startDate, $this->endDate) && $invoice->isValid())->count());

        $taxables_count = $taxpayers->flatMap(
            fn($taxpayer) => $taxpayer->invoices->filter(
                fn($invoice) => $invoice->created_at->between($this->startDate, $this->endDate) && $invoice->isValid()
            )->flatMap(fn($invoice) => $invoice->invoiceitems)
        )->unique('id')->count();

        $invoices_total = 0;
        foreach ($taxpayers as $taxpayer) {
            $this->processInvoices($taxpayer, $taxables, $labels, $invoices_total);
        }

        $labels = array_filter($labels, fn($item) => $item['total'] > 0);
        $taxables = array_filter($taxables, fn($item) => $item['total'] > 0);

        return [$labels, $taxables, $invoices_total, $taxpayer_count, $taxables_count, $invoice_count];
    }


    private function getTaxpayersWithInvoices()
    {
        return $this->getTaxpayerQuery()
            ->with(['invoices.invoiceitems.taxpayer_taxable.taxable.tax_label'])
           ->distinct()
            ->get();
    }


    private function getActiveLabels($categorieName)
    {
        return TaxLabel::where('status', 'ACTIVE')
            ->where('category', 'LIKE', '%' . $categorieName . '%')
            ->get(['id', 'name', 'code'])
            ->mapWithKeys(fn($item) => [
                $item->id => ['name' => $item->name, 'code' => $item->code, 'total' => 0]
            ])
            ->toArray();
    }


    private function getActiveTaxables()
    {
        return Taxable::where('status', 'ACTIVE')
            ->with(['tax_label'])
            ->get(['id', 'name', 'tax_label_id'])
            ->mapWithKeys(fn($item) => [
                $item->id => [
                    'name' => $item->name,
                    'code' => $item->tax_label?->code,
                    'total' => 0
                ]
            ])
            ->toArray();
    }


    private function processInvoices($taxpayer, &$taxables, &$labels, &$invoices_total)
    {
        $invoices = $taxpayer->invoices;
        foreach ($invoices as $invoice) {
            if ($invoice->created_at->between($this->startDate, $this->endDate) &&  $invoice->isValid()) {
                $invoices_total += $invoice->amount;
                foreach ($invoice->invoiceitems as $invoice_item) {
                    $taxpayerTaxable = $invoice_item->taxpayer_taxable;
                    if (isset($taxables[$taxpayerTaxable->taxable->id])) {
                        $taxables[$taxpayerTaxable->taxable->id]['total'] += $invoice_item->amount;
                    }
                    if (isset($labels[$taxpayerTaxable->taxable->tax_label->id])) {
                        $labels[$taxpayerTaxable->taxable->tax_label->id]['total'] += $invoice_item->amount;
                    }
                }
            }
        }
    }

    protected function getAllStatistics(): array
    {
        return [
            StatisticKeysEnums::BY_CATEGORY => $this->countTaxpayersByCategory(),
            StatisticKeysEnums::BY_ACTIVITY => $this->countTaxpayersByActivity(),
            StatisticKeysEnums::BY_CANTON => $this->countTaxpayersByCanton(),
            StatisticKeysEnums::BY_TOWN => $this->countTaxpayersByTown(),
            StatisticKeysEnums::BY_ZONE => $this->countTaxpayersByZone(),
            StatisticKeysEnums::BY_TAXABLE => $this->countTaxpayersByTaxables(),
            StatisticKeysEnums::BY_STATE => $this->countTaxpayersState(),
            StatisticKeysEnums::BY_TAXLABEL => $this->countTaxpayersByTaxLabel(),
        ];
    }
    public function getStats(string|null $type = null): array
    {
        switch ($type) {
            case TaxpayerStaticsEnums::BY_GENDER:
                return $this->countTaxpayers();
            case TaxpayerStaticsEnums::BY_CATEGORY:
                return $this->countTaxpayersByCategory();
            case TaxpayerStaticsEnums::BY_ACTIVITY:
                return $this->countTaxpayersByActivity();
            case TaxpayerStaticsEnums::BY_CANTON:
                return $this->countTaxpayersByCanton();
            case TaxpayerStaticsEnums::BY_TOWN:
                return $this->countTaxpayersByTown();
            case TaxpayerStaticsEnums::BY_ZONE:
                return $this->countTaxpayersByZone();
            case TaxpayerStaticsEnums::BY_TAXABLE:
                return $this->countTaxpayersByTaxables();
            case InvoiceStaticsEnums::BY_INVOICE:
                return $this->countInvoices();
            case InvoiceStaticsEnums::BY_INVOICE_COMPTANT:
                return $this->countInvoices(Constants::INVOICE_TYPE_COMPTANT);
            case TaxpayerStaticsEnums::BY_LABEL:
                return $this->countTaxpayersByTaxLabel();
            default:
                return $this->getAllStatistics();
        }
    }
}
