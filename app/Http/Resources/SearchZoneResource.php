<?php

namespace App\Http\Resources;


use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Zone $resource
 */

class SearchZoneResource extends JsonResource
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
            // 'category' => $this->resource->category
        ];
    }
}
