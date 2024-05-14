<?php

namespace App\DataTables;

use App\Enums\PrintNameEnums;
use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Year;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class InvoicesDataTable extends DataTable
{
    protected $showId;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query, Request $request): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['invoice', 'status'])
            ->editColumn('taxpayers.name', function (Invoice $invoice) {
                return view('pages/invoices.columns._invoice', compact('invoice'));
            })
            ->editColumn('invoice_no', function (Invoice $invoice) {
                return $invoice->invoice_no;
            })
            ->editColumn('order_no', function (Invoice $invoice) {
                return view('pages/invoices.columns._order_no', compact('invoice'));
                //return $invoice->order_no;
            })
            ->editColumn('nic', function (Invoice $invoice) {
                return $invoice->nic;
            })
            ->editColumn('zones.name', function (Invoice $invoice) {
                return $invoice->taxpayer->zone->name ?? '-';
            })
            ->editColumn('taxpayers.address', function (Invoice $invoice) {
                return $invoice->taxpayer->address ?? '-';
            })
            ->editColumn('taxpayers.latitude', function (Invoice $invoice) {
                return ($invoice->taxpayer->latitude ?? '-').' : '.($invoice->taxpayer->longitude ?? '-');
            })
            ->editColumn('tax_labels.code', function (Invoice $invoice) {
                return $invoice->invoiceitems()->first()->taxpayer_taxable->taxable->tax_label->code ?? '';
            })
            ->editColumn('total', function (Invoice $invoice) {
                if ($invoice->reduce_amount != '')
                    return '-' . $invoice->reduce_amount ;
                else
                   return $invoice->amount;
                //return $invoice->amount;
            })
            ->editColumn('paid', function (Invoice $invoice) {

                    return $invoice::getPaid($invoice->invoice_no);
            })
            ->editColumn('remains_to_be_paid', function (Invoice $invoice) {
                if($invoice->status== InvoiceStatusEnums::REDUCED|| $invoice->status== InvoiceStatusEnums::CANCELED||  $invoice->status== InvoiceStatusEnums::REJECTED)
                    return "-";
                elseif ($invoice->status== InvoiceStatusEnums::APPROVED_CANCELLATION){
                    $invoice =Invoice::where('invoice_no', $invoice->invoice_no)->first();
                    return $invoice::getRestToPaid($invoice);
                }
                else
                    return $invoice::getRestToPaid($invoice);
            })
            ->editColumn('validity', function (Invoice $invoice) {
                return view('pages/invoices.columns._validity', compact('invoice'));
                //return ''; // Return empty string
            })

            ->editColumn('status', function (Invoice $invoice) {
                return view('pages/invoices.columns._aproval', compact('invoice'));
            })
            ->editColumn('delivery_date', function (Invoice $invoice) {
                //return $invoice->delivery_date;
                return view('pages/invoices.columns._delivery', compact('invoice'));
            })
           // ->editColumn('from_date', function (Invoice $invoice) {return $invoice->from_date;})
            ->editColumn('to_date', function (Invoice $invoice) {
                return $invoice->to_date;})
            ->addColumn('action', function (Invoice $invoice) {
                return view('pages/invoices.columns._actions', compact('invoice'));
            })
            ->setRowId('uuid');
    }


    // public function query(Invoice $model): QueryBuilder
    // {
    //     return $query = $model->newQuery();
    // }

    // use Illuminate\Database\Eloquent\Builder;

    public function query(Invoice $model): QueryBuilder
    {

            $query= $model->join('invoice_items', 'invoice_items.invoice_id', '=', 'invoices.id')
                        ->leftjoin('taxpayers', 'taxpayers.id', '=', 'invoices.taxpayer_id')
                        ->join('taxpayer_taxables', 'taxpayer_taxables.id', '=', 'invoice_items.taxpayer_taxable_id')
                        ->join('taxables', 'taxables.id', '=', 'taxpayer_taxables.taxable_id')
                        ->join('tax_labels', 'tax_labels.id', '=', 'taxables.tax_label_id')
                        ->leftjoin('zones', 'zones.id', '=', 'taxpayers.zone_id')
                        // ->where('taxpayers.zone_id', 'LIKE', '%' . ($this->zone ?? '') . '%')
                        // ->where('taxables.tax_label_id', 'LIKE', '%' . ($this->taxlabel ?? '') . '%')
                        // ->where('invoices.validity', 'EXPIRED')
                        ->select('invoices.*')
                ->where('invoices.status','!=',InvoiceStatusEnums::REJECTED_BY_OR)
                        ->whereBetween('invoices.created_at', [$this->startDate, $this->endDate])
                        ->distinct()
                ->orderBy('invoices.created_at', 'desc')
                        ->newQuery();


        if ($this->notDelivery!==null && $this->notDelivery) {
            $query->whereNull('delivery_date');
        }elseif ($this->notDelivery!==null && !$this->notDelivery)  {
            $query->whereNotNull('delivery_date');
        }
        if ($this->startInvoiceId!== null && $this->endInvoiceId!== null) {
            $query->whereBetween('invoices.id', [$this->startInvoiceId, $this->endInvoiceId]);
        }
        if($this->aucomptant){
            $query->where('invoices.type','=',Constants::INVOICE_TYPE_COMPTANT);
        }else{
            $query->where('invoices.type','=',Constants::INVOICE_TYPE_TITRE);
        }
        return $query;
    }




    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('invoices-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(3)
            ->pageLength(100) // Set the default number of rows per page to 3
            ->lengthMenu([[100,300, 500,  -1], [100,300, 500, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayer_taxables/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('taxpayers.name')->title(__('taxpayer'))->addClass('d-flex align-items-center'),
            Column::make('invoice_no')->title(__('invoice no')),
            Column::make('order_no')->title(__('order no')),
            Column::make('nic')->title(__('nic'))->visible(false),
            Column::make('zones.name')->title(__('zone')),
            Column::make('taxpayers.address')->title(__('address'))->visible(false),
            Column::make('taxpayers.latitude')->title(__('gps'))->visible(false),
            Column::make('tax_labels.code')->title(__('code')),
            Column::make('total')->title(__('amount'))->name('amount'),
            Column::make('paid')->title(__('Montant payé'))->name('paid'),
            Column::make('remains_to_be_paid')->title(__('Reste'))->name('remains_to_be_paid'),
            Column::make('status')->title(__('aproval')),
            Column::make('delivery_date')->title( __('delivery date'))->addClass('text-nowrap'),
            //Column::make('from_date')->title( __('from_date'))->addClass('text-nowrap'),
            Column::make('to_date')->title( __('expiry date'))->addClass('text-nowrap'),
            Column::make('validity')->title(__('status')),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(true)
                ->printable(true)
                ->width(60)
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Invoices_' . date('YmdHis');
    }
}
