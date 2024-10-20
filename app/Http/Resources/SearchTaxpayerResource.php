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
            'id'=>$this->resource->id,
            'name' => $this->resource->name,
            'gender' => $this->resource->gender,
            'mobilephone' => $this->resource->mobilephone,
            'telephone' => $this->resource->telephone,
            'email' => $this->resource->email,
            'longitude' => $this->resource->longitude,
            'latitude' => $this->resource->latitude,
            'address' => $this->resource->address,
            'idType'=> $this->resource->id_type,
            'idNumber' =>  $this->resource->id_number,

            'categoryId' => $this->resource->category_id,
            'activityId' => $this->resource->activity_id,

            // 'category' => $this->resource->category!=null? new CategoryResource($this->resource->category) :null,
            // 'activity' => $this->resource->activity!=null?new ActivityResource($this->resource->activity) :null,

            'otherWork' => $this->resource->other_work,
            'fileNo' =>$this->resource->file_no,
            'authorisation' =>$this->resource->authorisation,
            'authReference' => $this->resource->auth_reference,
            'nif' =>  $this->resource->nif,

            'townId' => $this->resource->town_id,
            'ereaId' => $this->resource->erea_id,
            'zoneId' => $this->resource->zone_id,

            // 'town' => $this->resource->town!=null?new TownResource($this->resource->town):null,
            // 'erea' => $this->resource->erea!=null?new EreaResource( $this->resource->erea)  :null,
            // 'zone' => $this->resource->zone!=null?new ZoneResource($this->resource->zone):null,

        ];
    }
}
