<?php

namespace App\Exports;

use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoiceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $invoiceIds;
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate, ?array $invoiceIds = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->invoiceIds = $invoiceIds;
    }
    public function collection()
    {
        $query = Invoice::with(['taxpayer', 'taxpayer.zone',]);

        if (!empty($this->invoiceIds)) {
            $query->whereIn('id', $this->invoiceIds);
        }

        return $query
            ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
            ->where('invoices.type', '=', Constants::TITRE)
            ->orderBy('invoices.created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            __('invoice no'),
            __('order no'),
            __('taxpayer id'),
            __('taxpayer'),
            __('zone'),
            __('code'),
            __('amount'),
            __('Montant payé'),
            __('Reste'),
            __('aproval'),
            __('invoice_type'),
            __('from_date'),
            __('expiry date'),
            __('created_at')
        ];
    }

    public function map($invoice): array
    {
        try {
            return [
                $invoice->invoice_no,
                $invoice->order_no,
                $invoice->taxpayer->id ,
                $invoice->taxpayer->name ?? '-',
                $invoice->taxpayer->zone->name ?? '-',
                implode(',', array_keys(Invoice::sumAmountsByTaxCode($invoice))),
                $this->getAmount($invoice),
                format_amount(Payment::getPaid($invoice->invoice_no)),
                format_amount($invoice->get_remains_to_be_paid()),
                $invoice->status,
                $invoice->type,
                $invoice->from_date,
                $invoice->to_date,
                $invoice->created_at->format('d/m/Y'),
            ];
        } catch (\Exception $exception) {
            return [
                '',
                '',
                '' ,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
            ];
        }
    }
    public function getAmount(Invoice $invoice)
    {
        if ($invoice->reduce_amount != '') {
            return '-' . format_amount($invoice->reduce_amount);
        } else {
            return format_amount($invoice->amount);
        }
    }
}
