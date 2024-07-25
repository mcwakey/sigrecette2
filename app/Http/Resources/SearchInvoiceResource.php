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
            'taxpayerId' => $this->resource->taxpayer_id,
            'invoiceNo' => $this->resource->invoice_no,
            'nic' => $this->resource->nic,
            'orderNo' => $this->resource->order_no,
            'amount' => $this->resource->amount,
            'reduceAmount'=> $this->resource->auth_reference,
            'qty'=> $this->resource->qty,
            'fromDate'=> $this->resource->from_date,
            'toDate'=> $this->resource->to_date,
            'payStatus'=> $this->resource->pay_status,
            'status'=> $this->resource->status,
            'deliveryDate'=> $this->resource->delivery_date,
            'deliveryTo'=> $this->resource->delivery_to,
            'type'=> $this->resource->type,
            // 'invoiceitems'=> InvoiceItemResource::collection($this->invoiceitems)

        ];
    }
}
