<?php

namespace App\DataTables;

use App\Models\StockTransfer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class CollectorDepositsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            // ->filter(function ($query) {
            //     if (request()->filled('search.value')) {
            //         $query->where('tax_labels.name', 'like', '%' . request('search.value') . '%')
            //         ->orWhere('taxables.name', 'like', '%' . request('search.value') . '%')
            //         ->orWhere('tax_labels.code', 'like', '%' . request('search.value') . '%')
            //         ->orWhere('bill_status', 'like', '%' . request('search.value') . '%');
            //         // Add additional search conditions as needed for other columns
            //     }
            // })
            ->rawColumns(['status'])
            // ->editColumn('id', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->id;
            // })
            // ->editColumn('trans_no', function (StockTransfer $stock_transfer) {
            //     return view('pages.collector_deposits.columns._bill', compact('collector_deposit'));
            // })
            ->editColumn('stock_transfers.created_at', function (StockTransfer $stock_transfer) {
                return $stock_transfer->created_at->format('d M Y');
            })
            ->editColumn('taxables.name', function (StockTransfer $stock_transfer) {
                return $stock_transfer->taxable->name ?? '-';
            })
            ->editColumn('stock_transfers.code', function (StockTransfer $stock_transfer) {
                return $stock_transfer->code;
            })
            // ->editColumn('tax_labels.name', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->taxable->tax_label->name;
            // })
            ->editColumn('tariff', function (StockTransfer $stock_transfer) {
                return $stock_transfer->taxable->tariff;
            })
            // ->editColumn('collector_deposit', function (StockTransfer $stock_transfer) {
            //     return view('pages.collector_deposits.columns._label', compact('collector_deposit'));
            // })
            // ->editColumn('tax_type', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->;
            // })
            ->editColumn('stock_transfers.start_no', function (StockTransfer $stock_transfer) {
                return $stock_transfer->start_no. " - ". $stock_transfer->end_no;
                // return view('pages.collector_deposits.columns._seize', compact('collector_deposit'));
            })
            // ->editColumn('qty', function (StockTransfer $stock_transfer) {
            //     if ($stock_transfer->trans_type == "RECU") {
            //         $qty = $stock_transfer->qty ;
            //     } else {
            //         $qty =  "";
            //     }

            //     return $qty;
            // })
            // ->editColumn('total', function (StockTransfer $stock_transfer) {
            //     if ($stock_transfer->trans_type == "RECU") {
            //         $total = $stock_transfer->qty * $stock_transfer->taxable->tariff; ;
            //     } else {
            //         $total =  "";
            //     }

            //     return $total;
            // })
            ->editColumn('qty', function (StockTransfer $stock_transfer) {
                if ($stock_transfer->trans_type == "VENDU") {
                    $qty = $stock_transfer->qty ;
                } else {
                    $qty =  "";
                }

                return $qty;
            })
            ->editColumn('total', function (StockTransfer $stock_transfer) {
                if ($stock_transfer->trans_type == "VENDU") {
                    $total = $stock_transfer->qty * $stock_transfer->taxable->tariff; ;
                } else {
                    $total =  "";
                }

                return $total;
            })
            // ->editColumn('qty2', function (StockTransfer $stock_transfer) {
            //     if ($stock_transfer->trans_type == "RENDU") {
            //         $qty = $stock_transfer->qty ;
            //     } else {
            //         $qty =  "";
            //     }

            //     return $qty;
            // })
            // ->editColumn('total2', function (StockTransfer $stock_transfer) {
            //     if ($stock_transfer->trans_type == "RENDU") {
            //         $total = $stock_transfer->qty * $stock_transfer->taxable->tariff; ;
            //     } else {
            //         $total =  "";
            //     }

            //     return $total;
            // })
            // ->editColumn('qty2', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->qty;
            // })
            // ->editColumn('total2', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->qty * $stock_transfer->taxable->tariff;
            // })
            // ->editColumn('bill_status', function (StockTransfer $stock_transfer) {
            //     return view('pages.collector_deposits.columns._status', compact('collector_deposit'));
            // })
            // ->editColumn('location', function (StockTransfer $stock_transfer) {
            //     return view('pages.collector_deposits.columns._location', compact('collector_deposit'));
            // })
            ->editColumn('users.name', function (StockTransfer $stock_transfer) {
                return $stock_transfer->user->name ?? '-';
            })
            ->editColumn('stock_transfers.type', function (StockTransfer $stock_transfer) {
                return view('pages.stock_transfers.columns._status', compact('stock_transfer'));
                //return $stock_request->type;
            })
            ->editColumn('payments.reference', function (StockTransfer $stock_transfer) {
                return $stock_transfer->payment->reference ?? '';
                // return view('pages.collector_deposits.columns._seize', compact('collector_deposit'));
            })
            ->addColumn('action', function (StockTransfer $stock_transfer) {
                return view('pages.collector_deposits.columns._actions', compact('stock_transfer'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(): QueryBuilder // Remove $request parameter
    public function query(StockTransfer $model): QueryBuilder
    {
        return $model->join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')
                    // ->with('taxable.tax_label')
                    // ->join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
                    ->join('users', 'stock_transfers.to_user_id', '=', 'users.id')
                    ->leftjoin('payments', 'stock_transfers.payment_id', '=', 'payments.id')
                    ->where('stock_transfers.trans_type', 'VENDU') // Filter collector_deposits by taxpayer_id
                    ->select('stock_transfers.*')
                    //->orderBy('tax_labels.name')
                    ->newQuery();

        // return StockTransfer::where('taxpayer_id', $this->id); // Filter collector_deposits by taxpayer_id
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('collector_deposits-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            //->pageLength(3) // Set the default number of rows per page to 3
            //->lengthMenu([[3, 10, 25, 50, -1], [3, 10, 25, 50, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/collector_deposits/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title(__('id'))->exportable(false)->printable(false)->visible(false), 
            Column::make('stock_transfers.created_at')->title(__('date'))->addClass('text-nowrap'),
            //Column::make('trans_desc')->title(__('trans_desc')),
            // Column::make('tax_labels.name')->title(__('taxlabel')),
            Column::make('taxables.name')->title(__('ticket')),
            //Column::make('tariff')->title(__('tariff'))->addClass('text-nowrap')->name('tax_labels.name'),
            Column::make('stock_transfers.start_no')->title(__('num')),
            //Column::make('tax_type')->title(__('tax_type')),
            //Column::make('seize')->title(__('amount')),
            //Column::make('qty')->title(__('rc qty'))->addClass('text-nowrap'),
            //Column::make('total')->title(__('rc total')),
            Column::make('qty')->title(__('vv qty'))->name('qty'),
            Column::make('total')->title(__('vv total'))->name('qty'),
            //Column::make('qty2')->title(__('rd qty'))->addClass('text-nowrap'),
            //Column::make('total2')->title(__('rd total')),
            // Column::make('qty2')->title(__('sd qty'))->addClass('text-nowrap'),
            // Column::make('total2')->title(__('sd total')),
            Column::make('stock_transfers.code')->title(__('code')),
            Column::make('payments.reference')->title(__('reference no')),
            Column::make('stock_transfers.type')->title(__('status')),
            Column::make('users.name')->title(__('collector')),
            Column::computed('action')->title(__('action'))
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
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
        return 'StockTransfers_' . date('YmdHis');
    }
}
