<?php

namespace App\Http\Resources;

use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Town $resource
 */

class SearchTownResource extends JsonResource
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
            // 'code' => $this->resource->code,
            'cantonId' => $this->resource->canton_id
        ];
    }
}
