<?php

namespace App\DataTables;

use App\Models\Invoice;
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
            ->editColumn('taxpayer_id', function (Invoice $invoice) {
                return view('pages/invoices.columns._invoice', compact('invoice'));
            })
            ->editColumn('id', function (Invoice $invoice) {
                return $invoice->id;
            })
            ->editColumn('order_no', function (Invoice $invoice) {
                return $invoice->order_no;
            })
            ->editColumn('invoices.id', function (Invoice $invoice) {
                return $invoice->id.$invoice->taxpayer->id;
            })

            ->editColumn('zone', function (Invoice $invoice) {
                return $invoice->taxpayer->zone->name;
            })
            ->editColumn('taxpayer.address', function (Invoice $invoice) {
                return $invoice->taxpayer->address;
            })
            ->editColumn('gps', function (Invoice $invoice) {
                return $invoice->taxpayer->latitude . ' ,' . $invoice->taxpayer->longitude;
            })
            ->editColumn('total', function (Invoice $invoice) {
                return $invoice->amount;
            })
            ->editColumn('statuss', function (Invoice $invoice) {
                return sprintf('<div class="badge badge-light fw-bold">%s</div>', $invoice->status);
            })


            ->editColumn('status', function (Invoice $invoice) {
                return view('pages/invoices.columns._status', compact('invoice'));
            })


            ->editColumn('created_at', function (Invoice $invoice) {
                return $invoice->created_at->format('d M Y');
            })
            ->addColumn('action', function (Invoice $invoice) {
                return view('pages/invoices.columns._actions', compact('invoice'));
            })
            ->setRowId('id');
    }


    // public function query(Invoice $model): QueryBuilder
    // {
    //     return $query = $model->newQuery();
    // }

    // use Illuminate\Database\Eloquent\Builder;

    public function query(Invoice $model): QueryBuilder
    {
        return $model->newQuery()
            ->with('taxpayer', 'taxpayer.zone');
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
            ->orderBy(1)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/invoices/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('taxpayer_id')->title(__('taxpayer'))->addClass('d-flex align-items-center')->name('taxpayer'),
            Column::make('invoices.id')->title(__('invoice no')),
            Column::make('order_no')->title(__('order no')),
            Column::make('id')->title(__('nic')),

            Column::make('zone')->title(__('zone')),
            Column::make('taxpayer.address')->title(__('address')),
            Column::make('gps')->title(__('gps')),
            Column::make('total')->title(__('amount'))->name('amount'),
            Column::make('status')->title(__('status')),

            Column::make('created_at')->title( __('created Date'))->addClass('text-nowrap'),
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
