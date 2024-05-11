<?php

namespace App\Http\Resources;

use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Town $resource
 */

class TownResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->resource->name,
            'status' =>  $this->resource->status,
            'canton' => new CantonResource($this->canton),
        ];
    }
}
