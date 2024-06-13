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
            'id'=>$this->resource->id,
            'taxpayerId' => $this->resource->taxpayer_id,
            'invoiceId' => $this->resource->invoice_id,
            'description' => $this->resource->description,
            'reference' => $this->resource->reference,
            'amount' => $this->resource->amount,
            'remainingAmount'=> $this->resource->remaining_amount,
            'paymentType'=> $this->resource->payment_type,
            // 'fromDate'=> $this->resource->from_date,
            // 'toDate'=> $this->resource->to_date,
            // 'payStatus'=> $this->resource->pay_status,
            // 'status'=> $this->resource->status,
            'createdAt'=> $this->resource->created_at,
            'userId'=> $this->resource->user_id,
            'status'=> $this->resource->status,
            // 'invoiceitems'=> InvoiceItemResource::collection($this->invoiceitems)

        ];
    }
}
