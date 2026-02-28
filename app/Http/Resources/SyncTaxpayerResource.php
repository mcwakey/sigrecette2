<?php
namespace App\Http\Resources;

use App\Models\Taxpayer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Taxpayer $resource
 */
class SyncTaxpayerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'tnif' => $this->resource->tnif,
            'nif' => $this->resource->nif,
            'name' => $this->resource->name,
            'mobilephone' => $this->resource->mobilephone,
            'telephone' => $this->resource->telephone,
            'email' => $this->resource->email,
            'address' => $this->resource->address,
            'town_id' => $this->resource->town_id,
            'erea_id' => $this->resource->erea_id,
            'zone_id' => $this->resource->zone_id,
            'activity_id' => $this->resource->activity_id,
            'category_id' => $this->resource->category_id,
        ];
    }
}
