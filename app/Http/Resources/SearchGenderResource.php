<?php

namespace App\Http\Resources;

use App\Models\Gender;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Gender $resource
 */

class SearchGenderResource extends JsonResource
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
            // 'categoryId' => $this->resource->category_id
        ];
    }
}
