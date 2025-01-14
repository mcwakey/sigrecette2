<?php
namespace App\Contracts;
use App\Models\Year;
interface InvoiceStatisticsInterface
{
    public function countInvoices(): array;
    /**
     * @param array $validatedData
     */
    public function getTotalRemainingToBeCollected(): float|int;
}
