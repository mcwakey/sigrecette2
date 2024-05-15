<?php

namespace App\Http\Resources;


use App\Models\Taxable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Taxable $resource
 */

class SearchTaxableResource extends JsonResource
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
            'tariffType' => $this->resource->tariff_type,
            'unit' => $this->resource->unit,
            'unitType' => $this->resource->unit_type,
            'periodicity' => $this->resource->periodicity,
            'taxLabelId' => $this->resource->tax_label_id
        ];
    }
}
