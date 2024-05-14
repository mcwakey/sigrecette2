<?php

namespace App\Http\Resources;


use App\Models\Invoice;
use App\Models\Taxpayer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Invoice $resource
 */

class SearchInvoiceResource extends JsonResource
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
            'taxpayer_id' => $this->resource->taxpayer_id,
            'invoice_no' => $this->resource->invoice_no,
            'order_no' => $this->resource->longitude,
            'amount' => $this->resource->amount,
            'reduce_amount'=> $this->resource->auth_reference,
            'qty'=> $this->resource->qty,
            'from_date'=> $this->resource->from_date,
            'to_date'=> $this->resource->to_date,
            'pay_status'=> $this->resource->pay_status,
            'status'=> $this->resource->status,
            'delivery_date'=> $this->resource->delivery_date,
            'type'=> $this->resource->type,
            // 'invoiceitems'=> InvoiceItemResource::collection($this->invoiceitems)

        ];
    }
}
