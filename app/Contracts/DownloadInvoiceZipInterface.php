<?php

namespace App\Contracts;

use App\Dtos\InvoiceBatchDTO;

interface DownloadInvoiceZipInterface
{
    public function execute(InvoiceBatchDTO $dto):array;
}
