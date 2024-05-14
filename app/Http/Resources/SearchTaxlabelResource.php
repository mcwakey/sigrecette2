<?php

namespace App\Http\Resources;


use App\Models\TaxLabel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property TaxLabel $resource
 */

class SearchTaxlabelResource extends JsonResource
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
            'code' => $this->resource->code,
            'category' => $this->resource->category
        ];
    }
}
