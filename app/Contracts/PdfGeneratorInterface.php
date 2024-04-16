<?php

namespace App\Contracts;

interface PdfGeneratorInterface
{

    /**
     * @param array $data
     * @param string $template
     * @param int|null $action
     * @return array
     */
    public function generateInvoiceListPdf(array $data,string $template,int $action=null):array;

    /**
     * @param array $data
     * @param string $templateName
     * @param int|null $action
     * @return array
     */
    public function generateInvoicePdf(array $data,string $templateName,int $action=null ):array;

    /**
     * @param $data
     * @param string $template
     * @param $action
     * @return array
     */
    public function generateStateValueCollectorPdf($data, string $template, $action):array;

    /**
     * @param $data
     * @param $template
     * @param $action
     * @return array
     */
    public function generateStateValueReciepientPdf($data, $template, $action):array;

    /**
     * @param $data
     * @param string $template
     * @return array
     */
    public function generataxpayerFormPdf($data, string $template):array;


}
