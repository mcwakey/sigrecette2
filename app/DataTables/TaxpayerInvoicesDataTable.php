<?php

namespace App\DataTables;

use App\Models\Invoice;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class TaxpayerInvoicesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['invoice', 'status'])
            ->editColumn('invoice_no', function (Invoice $invoice) {
                return $invoice->invoice_no;
            })
            ->editColumn('order_no', function (Invoice $invoice) {
                return $invoice->order_no;
            })
            ->editColumn('nic', function (Invoice $invoice) {
                return $invoice->nic;
            })
            ->editColumn('total', function (Invoice $invoice) {
                return '';
            })
            ->editColumn('status', function (Invoice $invoice) {
                return view('pages.invoices.columns._status', compact('invoice'));
            })
            ->editColumn('created_at', function (Invoice $invoice) {
                return $invoice->created_at->format('d M Y');
            })
            ->addColumn('action', function (Invoice $invoice) {
                return view('pages.invoices.columns._actions', compact('invoice'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder // Remove $request parameter
    {
        return Invoice::where('taxpayer_id', $this->id); // Filter invoices by taxpayer_id
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('taxpayer_invoices-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/invoices/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('invoice_no')->title(__('invoice no')),
            Column::make('order_no')->title(__('order no')),
            Column::make('nic')->title(__('nic')),
            Column::make('total')->title(__('amount')),
            Column::make('status')->title(__('status')),
            Column::make('created_at')->title(__('created Date'))->addClass('text-nowrap'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(true)
                ->printable(true)
                ->width(60)
                // ->buttons(
                //     Button::make('create'),
                //     Button::make('export'),
                //     Button::make('print'),
                //     Button::make('reset'),
                //     Button::make('reload')
                // )
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'TaxpayerInvoices_' . date('YmdHis');
    }
}
