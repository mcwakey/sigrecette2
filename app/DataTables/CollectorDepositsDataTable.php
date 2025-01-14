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
            ->rawColumns(['status'])
            ->editColumn('stock_transfers.created_at', function (StockTransfer $stock_transfer) {
                return $stock_transfer->created_at->format('d M Y');
            })
            ->editColumn('taxables.name', function (StockTransfer $stock_transfer) {
                return $stock_transfer->taxable->name ?? '-';
            })
            ->editColumn('stock_transfers.code', function (StockTransfer $stock_transfer) {
                return $stock_transfer->code;
            })
            ->editColumn('tariff', function (StockTransfer $stock_transfer) {
                return $stock_transfer->taxable->tariff;
            })
            ->editColumn('stock_transfers.start_no', function (StockTransfer $stock_transfer) {
                return $stock_transfer->start_no . " - " . $stock_transfer->end_no;
            })
            ->editColumn('qty', function (StockTransfer $stock_transfer) {
                return $stock_transfer->trans_type == "VENDU" ? $stock_transfer->qty : "";
            })
            ->editColumn('total', function (StockTransfer $stock_transfer) {
                return $stock_transfer->trans_type == "VENDU" ? $stock_transfer->qty * $stock_transfer->taxable->tariff : "";
            })
            ->editColumn('users.name', function (StockTransfer $stock_transfer) {
                return $stock_transfer->user->name ?? '-';
            })
            ->editColumn('stock_transfers.type', function (StockTransfer $stock_transfer) {
                return view('pages.stock_transfers.columns._status', ['stock_transfer' => $stock_transfer]);
            })
            ->editColumn('payments.reference', function (StockTransfer $stock_transfer) {
                return $stock_transfer->payment->reference ?? '';
            })
            ->addColumn('action', function (StockTransfer $stock_transfer) {
                return view('pages.collector_deposits.columns._actions', ['stock_transfer' => $stock_transfer]);
            })
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(StockTransfer $model): QueryBuilder
    {
        $query = $model->join('taxables', 'stock_transfers.taxable_id', '=', 'taxables.id')
            ->join('users', 'stock_transfers.to_user_id', '=', 'users.id')
            ->leftjoin('payments', 'stock_transfers.payment_id', '=', 'payments.id')
            ->where('stock_transfers.trans_type', 'VENDU') // Filter collector_deposits by taxpayer_id
            ->select('stock_transfers.*')//->orderBy('tax_labels.name')
        ;
        if ($this->id) {
            $query = $query->where('to_user_id', '=', $this->id);
        }
        return $query->newQuery();
    }
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $columns = $this->getColumns();
        return $this->builder()
            ->setTableId('collector_deposits-table')
            ->language('../../public/assets/js/datatable-fr.json')
            ->columns($columns)
            ->minifiedAjax()
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
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
            Column::make('taxables.name')->title(__('ticket')),
            Column::make('stock_transfers.start_no')->title(__('num')),
            Column::make('qty')->title(__('vv qty'))->name('qty'),
            Column::make('total')->title(__('vv total'))->name('qty'),
            Column::make('stock_transfers.code')->title(__('code')),
            Column::make('payments.reference')->title(__('reference no')),
            Column::make('stock_transfers.type')->title(__('status')),
            Column::make('users.name')->title(__('collector')),
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
