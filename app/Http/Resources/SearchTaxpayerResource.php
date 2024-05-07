<?php

namespace App\Http\Resources;

use App\Models\Taxpayer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Taxpayer $resource
 */

class SearchTaxpayerResource extends JsonResource
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
            'gender' => $this->resource->gender,
            'mobilephone' => $this->resource->mobilephone,
            'telephone' => $this->resource->telephone,
            'longitude' => $this->resource->longitude,
            'latitude' => $this->resource->latitude,
            'town' =>$this->town?->id,
            'erea' => $this->erea?->id,
            'zone' => $this->zone?->id,
            'email' => $this->resource->email,
            //'last_login_at' =>  $this->resource->last_login_at,
            //'last_login_ip' =>  $this->resource->last_login_ip,
            //'profile_photo_path' => $this->resource->profile_photo_path,
        ];
    }
}
