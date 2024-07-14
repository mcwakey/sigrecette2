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
    public function mount()
    {
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
            'stats_reactive'=>[
                StatisticKeysEnums::BY_INVOICE=>$this->statisticsService->getStats(InvoiceStaticsEnums::BY_INVOICE),
                StatisticKeysEnums::BY_GENDER => $this->statisticsService->getStats( TaxpayerStaticsEnums::BY_GENDER)
            ]]);
    }
}
