<?php

namespace App\Http\Resources;

use App\Models\Erea;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Erea $resource
 */

class SearchEreaResource extends JsonResource
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
            // 'townId' => $this->resource->town_id
        ];
    }
}
