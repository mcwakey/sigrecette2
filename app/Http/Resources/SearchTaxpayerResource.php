<?php

namespace App\Http\Resources;

use App\Models\Activity;
use App\Models\Category;
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
            'id'=>$this->resource->id,
            'name' => $this->resource->name,
            'gender' => $this->resource->gender,
            'mobilephone' => $this->resource->mobilephone,
            'telephone' => $this->resource->telephone,
            'email' => $this->resource->email,
            'longitude' => $this->resource->longitude,
            'latitude' => $this->resource->latitude,
           'address' => $this->resource->address,
            'idType '=> $this->resource->id_type,
            'idNumber' =>  $this->resource->id_number,
            'categoryId' => new CategoryResource($this->resource->category_id) ,
            'activityId' =>new ActivityResource($this->resource->activity_id) ,
            'otherWork' => $this->resource->other_work,
                'fileNo' =>$this->resource->file_no,
            'authorisation' =>$this->resource->authorisation,
            'authReference' => $this->resource->auth_reference,
        'nif' =>  $this->resource->nif,
            'townId' => new TownResource($this->town?->id),
            'ereaId' => new EreaResource( $this->resource->erea?->id)  ,
            'zoneId' => new ZoneResource($this->resource->zone?->id)

        ];
    }
}
