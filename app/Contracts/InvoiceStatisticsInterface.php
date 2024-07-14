<?php

namespace App\Contracts;

use App\Models\Year;

interface InvoiceStatisticsInterface
{
    /**
     * @return array
     */
    public function countInvoices(Year $year):array;



}
