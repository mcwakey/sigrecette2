<?php

namespace App\Dtos;

class InvoiceBatchDTO
{
    /**
     * @param string[] $uuids
     */
    public function __construct(
        public  array $uuids,
        public string|null $action,
    ) {}
}
