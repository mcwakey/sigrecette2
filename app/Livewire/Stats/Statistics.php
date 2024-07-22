<?php

namespace App\Livewire\Stats;

use App\Enums\InvoiceStaticsEnums;
use App\Enums\StatisticKeysEnums;
use App\Enums\TaxpayerStaticsEnums;
use App\Services\StatisticsService;
use Livewire\Attributes\On;
use Livewire\Component;
use Carbon\Carbon;
class Statistics extends Component
{
    protected $statisticsService;
    public  $startDate;
    public  $endDate;
    public function mount( $startDate,$endDate)
    {
        $this->endDate= $endDate->toDateString();
        $this->startDate =$startDate->toDateString();;
        $this->assignStats();
    }
    public function assignStats()
    {
        $this->statisticsService = new StatisticsService();

    }

    #[On('fetchStats')]
    public function fetchStats()
    {
        $this->assignStats();
    }
    public function render()
    {
        $startDate = Carbon::parse($this->startDate);
        $endDate = Carbon::parse($this->endDate);
        return view('livewire.stats.statistics',[
            'invoice_count' => $this->statisticsService->getTotalRemainingToBeCollected($startDate, $endDate),
            'stats_reactive'=>[
                StatisticKeysEnums::BY_INVOICE=>$this->statisticsService->getStats(InvoiceStaticsEnums::BY_INVOICE),
                StatisticKeysEnums::BY_GENDER => $this->statisticsService->getStats( TaxpayerStaticsEnums::BY_GENDER)
            ]]);
    }
}
