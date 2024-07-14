<?php

namespace App\Contracts;

use App\Models\Year;

interface TaxpayerStatisticsInterface
{


    /**
     * @param Year $year
     * @return array
     */
    public function countTaxpayers(Year $year): array;

    /**
     * @param Year $year
     * @return array
     */
    public function countTaxpayersByCategory(Year $year): array;

    /**
     * @param Year $year
     * @return array
     */
    public function countTaxpayersByActivity(Year $year): array;


    /**
     * @param Year $year
     * @return array
     */
    public function countTaxpayersByCanton(Year $year): array;

    /**
     * @param Year $year
     * @return array
     */
    public function countTaxpayersByTown(Year $year): array;
    /**
     * @return array
     */
    public function countTaxpayersByZone(Year $year): array;

    /**
     * @param Year $year
     * @return array
     */
    public function countTaxpayersState(Year $year): array;


    /**
     * @param Year $year
     * @return array
     */
    public function countTaxpayersByTaxables(Year $year): array;

}
