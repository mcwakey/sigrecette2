<?php

namespace App\DataTables;

use App\Enums\InvoicePayStatusEnums;
use App\Enums\PrintNameEnums;
use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
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

class InvoicesDataTable extends DataTable
{
    use WithExportQueue;


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
                    return '-'. format_amount($invoice->reduce_amount) ;
                else
                   return format_amount($invoice->amount);
            })
            ->editColumn('paid', function (Invoice $invoice) {

                    return format_amount($invoice::getPaid($invoice->invoice_no));
            })
            ->editColumn('remains_to_be_paid', function (Invoice $invoice) {
                    return  format_amount($invoice->get_remains_to_be_paid());
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
            ->editColumn('reason_for_reject', function (Invoice $invoice) {
                return $invoice->reason_for_reject;})
            ->addColumn('action', function (Invoice $invoice) {
                return view('pages/invoices.columns._actions', compact('invoice'));
            })
            ->setRowId('uuid');
    }



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
        if($this->type!=null){
            $query->where('invoices.type','=',$this->type);
        }
        if ($this->startInvoiceId!== null && $this->endInvoiceId!== null) {
            $query->whereBetween('invoices.id', [$this->startInvoiceId, $this->endInvoiceId]);
        }
        if($this->state!=null){
            if($this->state==InvoiceStatusEnums::APPROVED){
                $query->whereIn('invoices.status',[InvoiceStatusEnums::APPROVED,InvoiceStatusEnums::APPROVED_CANCELLATION]);
            }else{
                $query->where('invoices.status','=',$this->state);
            }


        }
        if($this->delivery){
            if ($this->delivery==Constants::INVOICE_DELIVERY_LIV_KEY) {
                $query->whereNotNull('delivery_date');
                if($this->to_paid){
                    $query->whereIn('invoices.status',[InvoiceStatusEnums::APPROVED,InvoiceStatusEnums::APPROVED_CANCELLATION])
                    ->where('invoices.pay_status','!=',InvoicePayStatusEnums::PAID);

                }
            }elseif ( $this->delivery==Constants::INVOICE_DELIVERY_NON_LIV_KEY)  {
                $query->whereIn('invoices.status',[InvoiceStatusEnums::APPROVED,InvoiceStatusEnums::APPROVED_CANCELLATION]);

                $query->whereNull('delivery_date');

            }
        }


        if($this->id){
            $query->where('invoices.taxpayer_id','=',$this->id);
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
            ->minifiedAjax(route("invoices.index",request()->all()))
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(3)
            ->pageLength(100) // Set the default number of rows per page to 3
            ->lengthMenu([[100,300, 500,  -1], [100,300, 500, "All"]]) // Define options for the number of rows per page

            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayer_taxables/columns/_draw-scripts.js')) . "}");
    }

    public function getColumns(): array
    {
        $columns = [
            Column::make('taxpayers.name')->title(__('taxpayer'))->addClass('d-flex align-items-center'),
            Column::make('invoice_no')->title(__('invoice no')),
            Column::make('order_no')->title(__('order no')),
            Column::make('nic')->title(__('nic'))->visible(false),
            Column::make('zones.name')->title(__('zone')),
            Column::make('taxpayers.address')->title(__('address'))->visible(false),
            Column::make('taxpayers.latitude')->title(__('gps'))->visible(false),
            Column::make('tax_labels.code')->title(__('code'))->visible(false),
            Column::make('total')->title(__('amount'))->name('amount'),
            Column::make('paid')->title(__('Montant payé'))->name('paid')->searchable(false),
            Column::make('remains_to_be_paid')->title(__('Reste'))->name('remains_to_be_paid')->searchable(false),
            Column::make('status')->title(__('aproval')),
            Column::make('delivery_date')->title(__('delivery date'))->addClass('text-nowrap'),
            Column::make('from_date')->title(__('from_date'))->addClass('text-nowrap'),
            Column::make('to_date')->title(__('expiry date'))->addClass('text-nowrap')->visible(false),
            Column::make('validity')->title(__('status')),
            Column::make('taxpayer_id')->visible(false),
            Column::make('reason_for_reject')->title(__('reason_for_reject')),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(true)
                ->printable(true)
                ->width(60)
        ];


            $columns = array_map(function ($column) {
                if ($this->type==Constants::INVOICE_TYPE_COMPTANT){
                    if (in_array($column->name, ['zones.name','remains_to_be_paid','reason_for_reject'])) {
                        $column->visible(false);
                    }
                }
                if ($this->state != null) {
                    if ($this->state == InvoiceStatusEnums::DRAFT) {
                        if (in_array($column->name, ['order_no', 'paid', 'remains_to_be_paid', 'delivery_date', 'to_date', 'validity','reason_for_reject'])) {
                            $column->visible(false);
                        }
                    } elseif ($this->state == InvoiceStatusEnums::ACCEPTED) {
                        if (in_array($column->name, ['paid', 'remains_to_be_paid', 'delivery_date', 'validity', 'to_date','reason_for_reject'])) {
                            $column->visible(false);
                        }
                    }

                    elseif ($this->state == InvoiceStatusEnums::PENDING) {
                        if (in_array($column->name, ['paid', 'remains_to_be_paid', 'to_date', 'validity','delivery_date','reason_for_reject'])) {
                            $column->visible(false);
                        }
                    }elseif ($this->state==InvoiceStatusEnums::REJECTED){
                        if (in_array($column->name, ['paid', 'remains_to_be_paid', 'to_date', 'validity','delivery_date'])) {
                            $column->visible(false);
                        }
                    }
                }


                return $column;
            }, $columns);


        return $columns;
    }


    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Invoices_' . date('YmdHis');
    }
}
