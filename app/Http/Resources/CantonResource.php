<?php

namespace App\Http\Resources;

use App\Models\Canton;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Canton $resource
 */


class CantonResource extends JsonResource
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
            'status' => $this->resource->status,
        ];
    }
}
