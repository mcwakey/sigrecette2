<?php

namespace App\Livewire\Stats;

use App\Enums\InvoiceStaticsEnums;
use App\Enums\StatisticKeysEnums;
use App\Enums\TaxpayerStaticsEnums;
use App\Services\StatisticsService;
use Livewire\Attributes\On;
use Livewire\Component;

class Statistics extends Component
{
    protected $statisticsService;
    protected  $startDate;
    protected  $endDate;
    public function mount( $startDate,$endDate)
    {
        $this->endDate= $endDate;
        $this->startDate = $startDate;
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
        return view('livewire.stats.statistics',[
            'invoice_count'=> $this->statisticsService->getTotalRemainingToBeCollected($this->startDate,$this->endDate),
            'stats_reactive'=>[
                StatisticKeysEnums::BY_INVOICE=>$this->statisticsService->getStats(InvoiceStaticsEnums::BY_INVOICE),
                StatisticKeysEnums::BY_GENDER => $this->statisticsService->getStats( TaxpayerStaticsEnums::BY_GENDER)
            ]]);
    }
}
