<?php

namespace App\Http\Resources;

use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property InvoiceItem $resource
 */
class InvoiceItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->resource->id,
            'taxpayer_taxable_id' => $this->resource->taxpayer_taxable_id,
            'qty'=> $this->resource->qty,
            'amount' => $this->resource->amount,
            'ii_tariff' => $this->resource->ii_tariff,
            'ii_seize'=> $this->resource->ii_seize,

            'invoice_id'=> $this->resource->invoice_id,

        ];
    }
}
