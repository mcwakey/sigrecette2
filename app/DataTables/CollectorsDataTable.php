<?php

namespace App\DataTables;

use App\Models\StockTransfer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectorsDataTable extends DataTable
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
            ->editColumn('id', function (StockTransfer $stock_transfer) {
                return $stock_transfer->id;
            })
            // ->editColumn('trans_no', function (StockTransfer $stock_transfer) {
            //     return view('pages.stock_transfers.columns._bill', compact('stock_transfer'));
            // })
            ->editColumn('stock_transfers.created_at', function (StockTransfer $stock_transfer) {
                return $stock_transfer->created_at->format('d M Y');
            })
            ->editColumn('taxables.name', function (StockTransfer $stock_transfer) {
                return $stock_transfer->taxable->name;
            })
            // ->editColumn('trans_desc', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->trans_desc;
            // })
            ->editColumn('taxable.tariff', function (StockTransfer $stock_transfer) {
                return $stock_transfer->taxable->tariff;
            })
            // ->editColumn('stock_transfer', function (StockTransfer $stock_transfer) {
            //     return view('pages.stock_transfers.columns._label', compact('stock_transfer'));
            // })
            // ->editColumn('tax_type', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->;
            // })
            ->editColumn('stock_transfers.start_no', function (StockTransfer $stock_transfer) {
                return $stock_transfer->start_no. " - ". $stock_transfer->end_no;
                // return view('pages.stock_transfers.columns._seize', compact('stock_transfer'));
            })
            ->editColumn('rc_qty', function (StockTransfer $stock_transfer) {
                // if ($stock_transfer->trans_type == "RECU") {
                    $qty = $stock_transfer->rc_qty ;
                // } else {
                //     $qty =  "";
                // }

                return $qty;
            })
            ->editColumn('rc_total', function (StockTransfer $stock_transfer) {
                //if ($stock_transfer->trans_type == "RECU") {
                    $rc_total = $stock_transfer->rc_qty * $stock_transfer->taxable->tariff; ;
                // } else {
                    // $rc_total =  "";
                // }

                return $rc_total;
            })
            ->editColumn('vv_qty', function (StockTransfer $stock_transfer) {
                if (!$stock_transfer->vv_qty) {
                    $vv_qty =  "";
                } else {
                    $vv_qty = $stock_transfer->vv_qty ;
                }

                return $vv_qty;
            })
            ->editColumn('vv_total', function (StockTransfer $stock_transfer) {
                if (!$stock_transfer->vv_qty) {
                    $vv_total =  "";
                } else {
                    $vv_total = $stock_transfer->vv_qty * $stock_transfer->taxable->tariff;
                }

                return $vv_total;
            })
            ->editColumn('rd_qty', function (StockTransfer $stock_transfer) {
                // if (!$stock_transfer->rd_qty) {
                    // $rd_qty =  "";
                // } else {
                    $rd_qty = $stock_transfer->rd_qty ;
                // }

                // if (is_null($stock_transfer->rd_qty)) {
                //     $rd_total = null;
                // } else {
                //     $rd_total = $stock_transfer->rd_qty * $stock_transfer->taxable->tariff;
                // }

                return $rd_qty;
            })
            ->editColumn('rd_total', function (StockTransfer $stock_transfer) {
                // if (!$stock_transfer->rd_qty) {
                //     $rd_total =  "";
                // } else {
                //     $rd_total = $stock_transfer->rd_qty * $stock_transfer->taxable->tariff;
                // }

                if (is_null($stock_transfer->rd_qty)) {
                    $rd_total = null;
                } else {
                    $rd_total = $stock_transfer->rd_qty * $stock_transfer->taxable->tariff;
                }

                return $rd_total;
            })
            // ->editColumn('qty2', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->qty;
            // })
            // ->editColumn('total2', function (StockTransfer $stock_transfer) {
            //     return $stock_transfer->qty * $stock_transfer->taxable->tariff;
            // })
            // ->editColumn('bill_status', function (StockTransfer $stock_transfer) {
            //     return view('pages.stock_transfers.columns._status', compact('stock_transfer'));
            // })
            // ->editColumn('location', function (StockTransfer $stock_transfer) {
            //     return view('pages.stock_transfers.columns._location', compact('stock_transfer'));
            // })
            ->editColumn('users.name', function (StockTransfer $stock_transfer) {
                // return $stock_transfer->user->name;
                return view('pages.stock_transfers.columns._collector', compact('stock_transfer'));
            })

            ->editColumn('stock_transfers.type', function (StockTransfer $stock_transfer) {
                return view('pages.stock_transfers.columns._status', compact('stock_transfer'));
                //return $stock_request->type;
            })
            ->editColumn('period', function (StockTransfer $stock_transfer) {
                // return $stock_transfer->period_from->format('d M Y')." - ".$stock_transfer->period_to->format('d M Y');
                return $stock_transfer->period_from." - ".$stock_transfer->period_to;
            })
            ->addColumn('action', function (StockTransfer $stock_transfer) {
                return view('pages.stock_transfers.columns._collector_actions', compact('stock_transfer'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(): QueryBuilder // Remove $request parameter
    public function query(StockTransfer $model): QueryBuilder
    {
        // return $model->join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')
        //             // ->with('taxable.tax_label')
        //             ->join('users', 'stock_transfers.to_user_id', '=', 'users.id')
        //             // ->join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
        //             // ->where('stock_transfers.taxpayer_id', $this->id) // Filter stock_transfers by taxpayer_id
        //             ->select('stock_transfers.*')
        //             //->orderBy('tax_labels.name')
        //             ->newQuery();

        return $model->join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')
                    ->join('users', 'stock_transfers.to_user_id', '=', 'users.id')
                    ->select('stock_transfers.to_user_id',
                            DB::raw('SUM(CASE WHEN trans_type = "RECU" THEN qty*tariff END) AS rc_qty'),
                            DB::raw('SUM(CASE WHEN trans_type = "VENDU" THEN qty*tariff END) AS vv_qty'),
                            DB::raw('SUM(CASE WHEN trans_type = "RENDU" THEN qty*tariff END) AS rd_qty'),
                            DB::raw('MAX(stock_transfers.id) AS id'),
                            // DB::raw('MAX(users.name) AS trans_no'),
                            //DB::raw('MAX(stock_transfers.trans_desc) AS trans_desc'),
                            DB::raw('MAX(stock_transfers.start_no) AS start_no'),
                            DB::raw('MAX(stock_transfers.end_no) AS end_no'),
                            DB::raw('MAX(stock_transfers.last_no) AS last_no'),
                            DB::raw('MIN(stock_transfers.trans_type) AS trans_type'),
                            DB::raw('MIN(stock_transfers.type) AS type'),
                            //DB::raw('MAX(stock_transfers.to_user_id) AS to_user_id'),
                            DB::raw('MAX(stock_transfers.period_from) AS period_from'),
                            DB::raw('MAX(stock_transfers.period_to) AS period_to'),
                            DB::raw('MAX(stock_transfers.created_at) AS created_at'),
                            DB::raw('MAX(stock_transfers.taxable_id) AS taxable_id'))
                    // ->where('stock_transfers.to_user_id', $this->id)
                    ->groupBy( 'stock_transfers.to_user_id', 'stock_transfers.period_to')
                    ->orderBy('trans_id', 'desc')
            //mask olders
            // ->where('stock_transfers.type','!=', 'ARCHIVED')
            ;

        // return StockTransfer::where('taxpayer_id', $this->id); // Filter stock_transfers by taxpayer_id
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stock_transfers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            //->pageLength(3) // Set the default number of rows per page to 3
            //->lengthMenu([[3, 10, 25, 50, -1], [3, 10, 25, 50, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/stock_transfers/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // //Column::make('id')->title(__('id'))->exportable(false)->printable(false)->visible(false),
            Column::make('stock_transfers.created_at')->title(__('date'))->addClass('text-nowrap'),
            // //Column::make('trans_desc')->title(__('trans_desc')),
            Column::make('users.name')->title(__('collector')),
            Column::make('period')->title(__('Période')),
            // Column::make('id')->title(__('id')),
            // Column::make('stock_transfers.start_no')->title(__('num')),
            // //Column::make('tax_type')->title(__('tax_type')),
            // //Column::make('seize')->title(__('amount')),
            Column::make('rc_qty')->title(__('rc total'))->name('qty'),
            // Column::make('rc_total')->title(__('rc total'))->name('qty'),
            Column::make('vv_qty')->title(__('vv total'))->name('qty'),
            // Column::make('vv_total')->title(__('vv total'))->name('qty'),
            Column::make('rd_qty')->title(__('rd total'))->name('qty'),
            // Column::make('rd_total')->title(__('rd total'))->name('qty'),
            // // Column::make('qty2')->title(__('sd qty'))->addClass('text-nowrap'),
            // // Column::make('total2')->title(__('sd total')),
            // //Column::make('location')->title(__('location'))->addClass('text-nowrap'),
            // Column::make('users.name')->title(__('collector')),
            Column::make('stock_transfers.type')->title(__('status')),
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
