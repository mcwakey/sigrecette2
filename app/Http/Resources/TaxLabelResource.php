<?php

namespace App\Http\Resources;

use App\Models\Taxable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @property Taxable $resource
 */
class TaxLabelResource extends JsonResource
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
            'category' => $this->resource->category,
            'code' => $this->resource->code,
        ];
    }

}
