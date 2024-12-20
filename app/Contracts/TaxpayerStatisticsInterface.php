<?php
namespace App\Contracts;
use App\Models\Year;
interface TaxpayerStatisticsInterface
{
    public function countTaxpayers(Year $year): array;
    public function countTaxpayersByCategory(Year $year): array;
    public function countTaxpayersByActivity(Year $year): array;
    public function countTaxpayersByCanton(Year $year): array;
    public function countTaxpayersByTown(Year $year): array;
    public function countTaxpayersByZone(Year $year): array;
    public function countTaxpayersState(Year $year): array;
    public function countTaxpayersByTaxables(Year $year): array;
}
