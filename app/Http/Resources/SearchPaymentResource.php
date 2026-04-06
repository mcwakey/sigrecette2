<?php

namespace App\Http\Resources;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Payment $resource
 */
class SearchPaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'taxpayerId' => $this->resource->taxpayer_id,
            'invoiceId' => $this->resource->invoice_id,
            'description' => $this->resource->description,
            'reference' => $this->resource->reference,
            'amount' => $this->resource->amount,
            'remainingAmount' => $this->resource->remaining_amount,
            'paymentType' => $this->resource->payment_type,
            'invoiceType' => $this->resource->invoice_type,
            'code' => $this->resource->code,
            'provider' => $this->resource->provider,
            'network' => $this->resource->network,
            'phoneNumber' => $this->resource->phone_number,
            'externalId' => $this->resource->external_id,
            'createdAt' => $this->resource->created_at,
            'userId' => $this->resource->user_id,
            'status' => $this->resource->status,
        ];
    }
}
