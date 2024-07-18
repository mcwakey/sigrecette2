<?php

namespace App\Contracts;

use App\Models\Year;

interface InvoiceStatisticsInterface
{
    /**
     * @param Year $year
     * @return array
     */
    public function countInvoices(Year $year):array;

    /**
     * @param array $validatedData
     * @return float|int
     */
    public function getTotalRemainingToBeCollected(string $startDate, string $endDate): float|int;


}
