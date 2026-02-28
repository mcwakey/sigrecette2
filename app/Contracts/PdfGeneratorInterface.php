<?php

namespace App\Contracts;

interface PdfGeneratorInterface
{
    /**
     * @param int|null $action
     */
    public function generateInvoiceListPdf(array $data, string $template, int $action = null): array;
    /**
     * @param int|null $action
     */
    public function generateInvoicePdf(array $data, string $templateName, int $action = null): array;
    /**
     * @param $data
     * @param $action
     */
    public function generateStateValueCollectorPdf($data, string $template, $action): array;
    /**
     * @param $data
     * @param $template
     * @param $action
     */
    public function generateStateValueReciepientPdf($data, $template, $action): array;
    /**
     * @param $data
     */
    public function generataxpayerFormPdf($data, string $template): array;
}
