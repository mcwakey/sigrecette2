<?php

namespace App\Http\Resources;


use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property TaxpayerTaxable $resource
 */

class SearchTaxpayerTaxableResource extends JsonResource
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
            'name' => $this->resource->name,
            'seize' => $this->resource->seize,
            'location' => $this->resource->location,
            'longitude' => $this->resource->longitude,
            'latitude' => $this->resource->latitude,
            'authorisation'=> $this->resource->authorisation,
            'authReference'=> $this->resource->auth_reference,
            'taxableId' => $this->resource->taxable_id,
            'invoiceId' => $this->resource->invoice_id,
            'billStatus' => $this->resource->bill_status,

            'taxpayerId' => $this->resource->taxpayer_id,
            // 'taxable'=> new TaxableResource($this->resource->taxable),
        ];
    }
}
