<?php
namespace App\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\StockRequest;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
class StockRequestsSumDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['status'])
            ->editColumn('req_no', function (StockRequest $stock_request) {
                return view('pages.stock_requests.columns._bill', ['stock_request' => $stock_request]);
            })
            ->editColumn('stock_requests.created_at', function (StockRequest $stock_request) {
                return $stock_request->created_at->format('d M Y');
            })
            ->editColumn('req_desc', function (StockRequest $stock_request) {
                return $stock_request->req_desc;
            })
            ->editColumn('stock_requests.type', function (StockRequest $stock_request) {
                return view('pages.stock_requests.columns._status', ['stock_request' => $stock_request]);
            })
            ->addColumn('action', function (StockRequest $stock_request) {
                return view('pages.stock_requests.columns._actions', ['stock_request' => $stock_request]);
            })
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(StockRequest $model): QueryBuilder
    {
        return $model->join('taxables', 'stock_requests.taxable_id', '=', 'taxables.id')
            ->join('users', 'stock_requests.user_id', '=', 'users.id')
            ->select('stock_requests.req_no',
                DB::raw('SUM(CASE WHEN req_type = "DEMANDE" THEN qty*tariff END) AS pc_qty'),
                DB::raw('SUM(CASE WHEN req_type = "VENDU" THEN qty*tariff END) AS vd_qty'),
                DB::raw('SUM(CASE WHEN req_type = "RENDU" THEN qty*tariff END) AS rd_qty'),
                DB::raw('MAX(stock_requests.id) AS id'),
                DB::raw('MAX(stock_requests.req_desc) AS req_desc'),
                DB::raw('MIN(stock_requests.req_type) AS req_type'),
                DB::raw('MIN(stock_requests.type) AS type'),
                DB::raw('MAX(stock_requests.created_at) AS created_at')
            )
            ->groupBy('stock_requests.req_no')
            ->orderBy('req_id', 'desc');
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
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/stock_requests/columns/_draw-scripts.js')) . "}");
    }
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('stock_requests.created_at')->title(__('date'))->addClass('text-nowrap'),
            Column::make('req_no')->title(__('req_no')),
            Column::make('req_desc')->title(__('req desc')),
            Column::make('pc_qty')->title(__('pc total')),
            Column::make('vd_qty')->title(__('vv total')),
            Column::make('rd_qty')->title(__('sd total')),
            Column::make('stock_requests.type')->title(__('status')),
            Column::computed('action')->title(__('action'))
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
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
