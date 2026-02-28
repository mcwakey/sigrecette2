<?php

namespace App\Contracts;

use App\Models\Year;

interface TaxpayerStatisticsInterface
{
    public function countTaxpayers(): array;
    public function countTaxpayersByCategory(): array;
    public function countTaxpayersByActivity(): array;
    public function countTaxpayersByCanton(): array;
    public function countTaxpayersByTown(): array;
    public function countTaxpayersByZone(): array;
    public function countTaxpayersState(): array;
    public function countTaxpayersByTaxables(): array;
}
