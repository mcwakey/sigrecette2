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
class StockTransfersDataTable extends DataTable
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
            ->editColumn('stock_transfers.created_at', function (StockTransfer $stock_transfer) {
                return $stock_transfer->created_at->format('d M Y');
            })
            ->editColumn('taxables.name', function (StockTransfer $stock_transfer) {
                return $stock_transfer->taxable->name;
            })
            ->editColumn('taxable.tariff', function (StockTransfer $stock_transfer) {
                return $stock_transfer->taxable->tariff;
            })
            ->editColumn('stock_transfers.start_no', function (StockTransfer $stock_transfer) {
                return $stock_transfer->start_no . " - " . $stock_transfer->end_no;
            })
            ->editColumn('rc_qty', function (StockTransfer $stock_transfer) {
                return $stock_transfer->rc_qty;
            })
            ->editColumn('rc_total', function (StockTransfer $stock_transfer) {
                return $stock_transfer->rc_qty * $stock_transfer->taxable->tariff;
            })
            ->editColumn('vv_qty', function (StockTransfer $stock_transfer) {
                if (!$stock_transfer->vv_qty) {
                    $vv_qty = $stock_transfer->rd_qty ? "0" : "";
                } else {
                    $vv_qty = $stock_transfer->vv_qty;
                }
                return $vv_qty;
            })
            ->editColumn('vv_total', function (StockTransfer $stock_transfer) {
                if (!$stock_transfer->vv_qty) {
                    $vv_total = $stock_transfer->rd_qty ? "0" : "";
                } else {
                    $vv_total = $stock_transfer->vv_qty * $stock_transfer->taxable->tariff;
                }
                return $vv_total;
            })
            ->editColumn('rd_qty', function (StockTransfer $stock_transfer) {
                return $stock_transfer->rd_qty;
            })
            ->editColumn('rd_total', function (StockTransfer $stock_transfer) {
                return is_null($stock_transfer->rd_qty) ? null : $stock_transfer->rd_qty * $stock_transfer->taxable->tariff;
            })
            ->editColumn('users.name', function (StockTransfer $stock_transfer) {
                return $stock_transfer->user->name;
            })
            ->editColumn('stock_transfers.type', function (StockTransfer $stock_transfer) {
                return view('pages.stock_transfers.columns._status', ['stock_transfer' => $stock_transfer]);
            })
            ->addColumn('action', function (StockTransfer $stock_transfer) {
                return view('pages.stock_transfers.columns._actions', ['stock_transfer' => $stock_transfer]);
            })
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(StockTransfer $model): QueryBuilder
    {
        return $model->join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')
            ->join('users', 'stock_transfers.to_user_id', '=', 'users.id')
            ->select('stock_transfers.trans_id',
                DB::raw('SUM(CASE WHEN trans_type = "RECU" THEN qty END) AS rc_qty'),
                DB::raw('SUM(CASE WHEN trans_type = "VENDU" THEN qty END) AS vv_qty'),
                DB::raw('MAX(CASE WHEN trans_type = "RENDU" THEN qty END) AS rd_qty'),
                DB::raw('MAX(stock_transfers.id) AS id'),
                DB::raw('MAX(stock_transfers.trans_no) AS trans_no'),
                DB::raw('MAX(stock_transfers.start_no) AS start_no'),
                DB::raw('MAX(stock_transfers.end_no) AS end_no'),
                DB::raw('MAX(stock_transfers.last_no) AS last_no'),
                DB::raw('MIN(stock_transfers.trans_type) AS trans_type'),
                DB::raw('MIN(stock_transfers.type) AS type'),
                DB::raw('MAX(stock_transfers.to_user_id) AS to_user_id'),
                DB::raw('MAX(stock_transfers.created_at) AS created_at'),
                DB::raw('MAX(stock_transfers.taxable_id) AS taxable_id'))
            ->whereBetween('stock_transfers.created_at', [$this->startDate, $this->endDate])
            ->where('stock_transfers.to_user_id', $this->id)
            ->where('stock_transfers.period_from', $this->dateFrom)
            ->groupBy('stock_transfers.trans_id')
            ->orderBy('trans_id', 'desc');
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
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/stock_transfers/columns/_draw-scripts.js')) . "}");
    }
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('stock_transfers.created_at')->title(__('date'))->addClass('text-nowrap'),
            Column::make('taxables.name')->title(__('ticket')),
            Column::make('taxable.tariff')->title(__('tariff')),
            Column::make('stock_transfers.start_no')->title(__('num')),
            Column::make('rc_qty')->title(__('rc qty'))->name('qty'),
            Column::make('rc_total')->title(__('rc total'))->name('qty'),
            Column::make('vv_qty')->title(__('vv qty'))->name('qty'),
            Column::make('vv_total')->title(__('vv total'))->name('qty'),
            Column::make('rd_qty')->title(__('rd qty'))->name('qty'),
            Column::make('rd_total')->title(__('rd total'))->name('qty'),
            Column::make('stock_transfers.type')->title(__('status')),
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
        return 'StockTransfers_' . date('YmdHis');
    }
}
