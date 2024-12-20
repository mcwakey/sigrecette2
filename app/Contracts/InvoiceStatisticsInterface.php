<?php
namespace App\Contracts;
use App\Models\Year;
interface InvoiceStatisticsInterface
{
    public function countInvoices(Year $year): array;
    /**
     * @param array $validatedData
     */
    public function getTotalRemainingToBeCollected(string $startDate, string $endDate): float|int;
}
