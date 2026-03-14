<?php
namespace App\Http\Resources;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Invoice $resource
 */
class SyncInvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->resource->uuid,
            'invoice_no' => $this->resource->invoice_no,
            'taxpayer_id' => $this->resource->taxpayer_id,
            'amount' => $this->resource->amount,
            'reduce_amount' => $this->resource->reduce_amount,
            'qty' => $this->resource->qty,
            'from_date' => $this->resource->from_date,
            'to_date' => $this->resource->to_date,
            'pay_status' => $this->resource->pay_status,
            'status' => $this->resource->status,
            'type' => $this->resource->type,
            'delivery' => $this->resource->delivery,
            'delivery_date' => $this->resource->delivery_date,
            'edition_state' => $this->resource->edition_state,
            'notes' => $this->resource->getNotes(),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'taxpayer' => SyncTaxpayerResource::make($this->whenLoaded('taxpayer')),
            'items' => SyncInvoiceItemResource::collection($this->whenLoaded('invoiceitems')),
        ];
    }
}
