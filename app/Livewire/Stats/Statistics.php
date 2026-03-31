<?php

namespace App\Livewire\Stats;

use App\Enums\InvoiceStaticsEnums;
use App\Enums\StatisticKeysEnums;
use App\Enums\TaxpayerStaticsEnums;
use App\Helpers\Constants;
use App\Services\StatisticsService;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;

class Statistics extends Component
{
    protected $statisticsService;
    public $startDate;
    public $endDate;
    public function mount($startDate, $endDate)
    {
        $this->endDate = $endDate;
        $this->startDate = $startDate;
        $this->assignStats();
    }
    public function assignStats()
    {
        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);
        $this->statisticsService = new StatisticsService($startDate, $endDate);
    }
    #[On('fetchStats')]
    public function fetchStats()
    {
        $this->assignStats();
    }
    public function render()
    {

       // dd($this->statisticsService->getTaxpayerByCreatedAt($startDate, $endDate)[0]);
        return view('livewire.stats.statistics', [
            'invoice_count_t' => $this->statisticsService->getTotalRemainingToBeCollected(),
            'invoice_estimation_t' => $this->statisticsService->getTotalSoldToBeCollected(),
            'invoice_count_c' => $this->statisticsService->getTotalRemainingToBeCollected(Constants::INVOICE_TYPE_COMPTANT),
            'invoice_estimation_c' => $this->statisticsService->getTotalSoldToBeCollected(Constants::INVOICE_TYPE_COMPTANT),
            'invoice_count_collected' => $this->statisticsService->getTotalCollected(),
            'stats_reactive' => [
                StatisticKeysEnums::BY_INVOICE_COMPTANT->value => $this->statisticsService->getStats(InvoiceStaticsEnums::BY_INVOICE_COMPTANT->value),
                StatisticKeysEnums::BY_INVOICE->value => $this->statisticsService->getStats(InvoiceStaticsEnums::BY_INVOICE->value),
                StatisticKeysEnums::BY_GENDER->value => $this->statisticsService->getStats(TaxpayerStaticsEnums::BY_GENDER->value)
            ]]);
    }
}
