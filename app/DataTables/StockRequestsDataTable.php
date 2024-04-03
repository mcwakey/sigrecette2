<?php

namespace App\DataTables;

use App\Models\StockRequest;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class StockRequestsDataTable extends DataTable
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
            // ->editColumn('id', function (StockRequest $stock_request) {
            //     return $stock_request->id;
            // })
            // ->editColumn('req_no', function (StockRequest $stock_request) {
            //     return view('pages.stock_requests.columns._bill', compact('stock_request'));
            // })
            ->editColumn('stock_requests.created_at', function (StockRequest $stock_request) {
                return $stock_request->created_at->format('d M Y');
            })
            ->editColumn('taxables.name', function (StockRequest $stock_request) {
                return $stock_request->taxable->name;
            })
            ->editColumn('req_desc', function (StockRequest $stock_request) {
                return $stock_request->req_desc;
            })
            ->editColumn('taxables.tariff', function (StockRequest $stock_request) {
                return $stock_request->taxable->tariff;
            })
            // ->editColumn('stock_request', function (StockRequest $stock_request) {
            //     return view('pages.stock_requests.columns._label', compact('stock_request'));
            // })
            // ->editColumn('tax_type', function (StockRequest $stock_request) {
            //     return $stock_request->;
            // })
            ->editColumn('stock_requests.start_no', function (StockRequest $stock_request) {
                return $stock_request->start_no. " - ". $stock_request->end_no;
                // return view('pages.stock_requests.columns._seize', compact('stock_request'));
            })
            ->editColumn('qty', function (StockRequest $stock_request) {
                if ($stock_request->req_type == "DEMANDE") {
                    $qty = $stock_request->qty ;
                } else {
                    $qty =  "";
                }

                return $qty;
            })
            ->editColumn('total', function (StockRequest $stock_request) {
                if ($stock_request->req_type == "DEMANDE") {
                    $total = $stock_request->qty * $stock_request->taxable->tariff; ;
                } else {
                    $total =  "";
                }

                return $total;
            })
            ->editColumn('qty1', function (StockRequest $stock_request) {
                if ($stock_request->req_type == "COMPTABILITE") {
                    $qty = $stock_request->qty ;
                } else {
                    $qty =  "";
                }

                return $qty;
            })
            ->editColumn('total1', function (StockRequest $stock_request) {
                if ($stock_request->req_type == "COMPTABILITE") {
                    $total = $stock_request->qty * $stock_request->taxable->tariff; ;
                } else {
                    $total =  "";
                }

                return $total;
            })
            ->editColumn('qty2', function (StockRequest $stock_request) {
                return $stock_request->qty;
            })
            ->editColumn('total2', function (StockRequest $stock_request) {
                return $stock_request->qty * $stock_request->taxable->tariff;
            })
            // ->editColumn('bill_status', function (StockRequest $stock_request) {
            //     return view('pages.stock_requests.columns._status', compact('stock_request'));
            // })
            ->editColumn('users.name', function (StockRequest $stock_request) {
                return $stock_request->user->name;
            })
            ->editColumn('stock_requests.type', function (StockRequest $stock_request) {
                return view('pages.stock_requests.columns._status', compact('stock_request'));
                //return $stock_request->type;
            })
            ->addColumn('action', function (StockRequest $stock_request) {
                return view('pages.stock_requests.columns._actions', compact('stock_request'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(): QueryBuilder // Remove $request parameter
    public function query(StockRequest $model): QueryBuilder
    {
        return $model->with('taxable')
                    ->join('taxables', 'stock_requests.taxable_id', '=', 'taxables.id')
                    // ->with('taxable.tax_label')
                    ->join('users', 'stock_requests.user_id', '=', 'users.id')
                    // ->where('stock_requests.taxpayer_id', $this->id) // Filter stock_requests by taxpayer_id
                    ->select('stock_requests.*')
                    //->orderBy('tax_labels.name')
                    ->newQuery();

        // return StockRequest::where('taxpayer_id', $this->id); // Filter stock_requests by taxpayer_id
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('stock_requests-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            //->pageLength(3) // Set the default number of rows per page to 3
            //->lengthMenu([[3, 10, 25, 50, -1], [3, 10, 25, 50, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/stock_requests/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('req_id')->title(__('id'))->exportable(false)->printable(false)->visible(false), 
            Column::make('stock_requests.created_at')->title(__('date'))->addClass('text-nowrap'),
            Column::make('req_desc')->title(__('req desc')),
            Column::make('taxables.name')->title(__('taxable')),
            Column::make('taxables.tariff')->title(__('tariff')),
            Column::make('stock_requests.start_no')->title(__('num')),
            //Column::make('tax_type')->title(__('tax_type')),
            //Column::make('seize')->title(__('amount')),
            Column::make('qty')->title(__('pc qty'))->name('qty'),
            Column::make('total')->title(__('pc total'))->name('qty'),
            Column::make('qty1')->title(__('vv qty'))->name('qty'),
            Column::make('total1')->title(__('vv total'))->name('qty'),
            // Column::make('qty2')->title(__('sd qty'))->addClass('text-nowrap'),
            // Column::make('total2')->title(__('sd total')),
            Column::make('users.name')->title(__('user')),
            Column::make('stock_requests.type')->title(__('status')),
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
        return 'StockRequests_' . date('YmdHis');
    }
}
