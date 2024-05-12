<?php

namespace App\Http\Resources;

use App\Models\Taxable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property Taxable $resource
 */
class TaxableResource extends JsonResource
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
            'tariff' => $this->resource->tariff,
            'tariff_type' => $this->resource->tariff_type,
            'unit' => $this->resource->unit,
            'unit_type'=> $this->resource->unit_type,
            'modality' => $this->resource->modality,
            'periodicity'=> $this->resource->periodicity,
            'penalty'=> $this->resource->penalty,
'penalty_type'=> $this->resource->penalty_type,
            'tax_label_id'=> $this->resource->tax_label_id
        ];
    }

}
