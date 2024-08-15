<?php

namespace App\DataTables;

use App\Enums\InvoicePayStatusEnums;
use App\Enums\PrintNameEnums;
use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Helpers\InvoiceHelper;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Year;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\WithExportQueue;

class ExportInvoicesDataTable extends DataTable
{
    use WithExportQueue;

    public function dataTable(QueryBuilder $query, Request $request): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('taxpayer.name', function (Invoice $invoice) {
                return $invoice->taxpayer->name;
            })
            ->editColumn('invoice_no', function (Invoice $invoice) {
                return $invoice->invoice_no;
            })
            ->editColumn('order_no', function (Invoice $invoice) {
                return $invoice->order_no;
            })
            ->editColumn('nic', function (Invoice $invoice) {
                return $invoice->nic;
            })
            ->editColumn('taxpayer.zone.name', function (Invoice $invoice) {
                return $invoice->taxpayer->zone->name ?? '-';
            })
            ->editColumn('tax_labels.code', function (Invoice $invoice) {
                return implode(',', array_keys(InvoiceHelper::sumAmountsByTaxCode($invoice)));
            })
            ->editColumn('total', function (Invoice $invoice) {
                if ($invoice->reduce_amount != '')
                    return '-'. format_amount($invoice->reduce_amount) ;
                else
                   return format_amount($invoice->amount);
            })
            ->editColumn('paid', function (Invoice $invoice) {

                    return format_amount(Payment::getPaid($invoice->invoice_no));
            })
            ->editColumn('remains_to_be_paid', function (Invoice $invoice) {
                    return  format_amount($invoice->get_remains_to_be_paid());
            })
            ->editColumn('validity', function (Invoice $invoice) {
                return __($invoice->validity);

            })

            ->editColumn('status', function (Invoice $invoice) {
                return __($invoice->status);
            })
            ->editColumn('delivery_date', function (Invoice $invoice) {
                return $invoice->delivery_date;
            })
           ->editColumn('from_date', function (Invoice $invoice) {return $invoice->from_date;})
            ->editColumn('to_date', function (Invoice $invoice) {
                return $invoice->to_date;})
            ->editColumn('reason_for_reject', function (Invoice $invoice) {
                return $invoice->reason_for_reject;})
            ->addColumn('type', function (Invoice $invoice) {
                return $invoice->type;
            })
            ->setRowId('uuid');
    }



    public function query(Invoice $model): QueryBuilder
    {

            $query= $model->with(['taxpayer','taxpayer.zone','invoice_items.taxpayer_taxable.taxable.tax_label' ])
                ->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
                        ->leftjoin('taxpayers', 'taxpayers.id', '=', 'invoices.taxpayer_id')
                        ->join('taxpayer_taxables', 'taxpayer_taxables.id', '=', 'invoice_items.taxpayer_taxable_id')
                        ->join('taxables', 'taxables.id', '=', 'taxpayer_taxables.taxable_id')
                        ->join('tax_labels', 'tax_labels.id', '=', 'taxables.tax_label_id')
                        ->leftjoin('zones', 'zones.id', '=', 'taxpayers.zone_id')
                        ->select('invoices.*')
                        ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
                        ->distinct()
                ->orderBy('invoices.created_at', 'desc')
                        ->newQuery();
        return $query;
    }
    public function getColumns(): array
    {
        $columns = [

            Column::make('invoice_no')->title(__('invoice no')),
            Column::make('order_no')->title(__('order no')),
            Column::make('taxpayer.name')->title(__('taxpayer')),
            Column::make('nic')->title(__('nic')),
            Column::make('taxpayer.zone.name')->title(__('zone')),
            Column::make('tax_labels.code')->title(__('code')),
            Column::make('total')->title(__('amount'))->name('amount'),
            Column::make('paid')->title(__('Montant payé'))->name('paid')->searchable(false),
            Column::make('remains_to_be_paid')->title(__('Reste'))->name('remains_to_be_paid')->searchable(false),
            Column::make('status')->title(__('aproval')),
            Column::make('delivery_date')->title(__('delivery date'))->addClass('text-nowrap'),
            Column::make('from_date')->title(__('from_date'))->addClass('text-nowrap'),
            Column::make('to_date')->title(__('expiry date'))->addClass('text-nowrap'),
            Column::make('validity')->title(__('status')),
            Column::make('taxpayer_id')->visible(false),
            Column::make('reason_for_reject')->title(__('reason_for_reject')),
            Column::make('type')->title(__('invoice_type')),
        ];


        return $columns;
    }



    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {

        return $this->builder()
            ->setTableId('export-invoices-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->pageLength(100)
            ->lengthMenu([[100,300, 500,  -1], [100,300, 500, "All"]])

            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayer_taxables/columns/_draw-scripts.js')) . "}");
    }




    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Export_Invoices_' . date('YmdHis');
    }
}
