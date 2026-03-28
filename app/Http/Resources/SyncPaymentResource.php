<?php
namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Payment $resource
 */
class SyncPaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->resource->uuid,
            'invoice_uuid' => $this->resource->invoice?->uuid,
            'invoice_id' => $this->resource->invoice_id,
            'taxpayer_id' => $this->resource->taxpayer_id,
            'amount' => $this->resource->amount,
            'payment_type' => $this->resource->payment_type,
            'invoice_type' => $this->resource->invoice_type,
            'reference' => $this->resource->reference,
            'description' => $this->resource->description,
            'remaining_amount' => $this->resource->remaining_amount,
            'status' => $this->resource->status,
            'deposit' => $this->resource->deposit,
            'notes' => $this->resource->notes,
            'provider' => $this->resource->provider,
            'phone_number' => $this->resource->phone_number,
            'external_id' => $this->resource->external_id,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
