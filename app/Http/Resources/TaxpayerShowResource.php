<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\EreaResource;
use App\Http\Resources\TownResource;
use App\Http\Resources\ZoneResource;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Taxpayer $resource
 */

class TaxpayerShowResource extends JsonResource
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
            'town' => new TownResource($this->town),
            'erea' => new EreaResource($this->erea),
            'zone' => new ZoneResource($this->zone),
            'email' => $this->resource->email,
            'last_login_at' =>  $this->resource->last_login_at,
            'last_login_ip' =>  $this->resource->last_login_ip,
            'profile_photo_path' => $this->resource->profile_photo_path,
        ];
    }
}
