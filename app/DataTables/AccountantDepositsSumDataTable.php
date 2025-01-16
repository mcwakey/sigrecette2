<?php
namespace App\DataTables;
use Illuminate\Support\Facades\DB;
use App\Enums\PaymentStatusEnums;
use App\Models\Payment;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
class AccountantDepositsSumDataTable extends DataTable
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
            ->editColumn('payments.created_at', function (Payment $payment) {
                return $payment->created_at->format('d M Y');
            })
            ->editColumn('reference_deposit', function (Payment $payment) {
                return view('pages.accountant_deposits.columns._reference', ['payment' => $payment]);
            })
            ->editColumn('status', function (Payment $payment) {
                return view('pages.accountant_deposits.columns._status', ['payment' => $payment]);
            })
            ->addColumn('action', function (Payment $payment) {
                return view('pages.accountant_deposits.columns._actions', ['payment' => $payment]);
            })
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(Payment $model): QueryBuilder
    {
        return $model
            ->select('id',
                DB::raw('SUM(amount) AS amount'),
                DB::raw('MAX(reference_deposit) AS reference_deposit'),
                DB::raw('MAX(status) AS status'),
                DB::raw('MAX(payments.updated_at) AS created_at')
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->where('status', '!=', PaymentStatusEnums::CANCELED)
            ->groupBy('reference_deposit', 'reference_deposit')
            ->orderBy('reference_deposit', 'asc');
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
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/accountant_deposits/columns/_draw-scripts.js')) . "}");
    }
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title(__('id'))->exportable(false)->printable(false)->visible(false),
            Column::make('payments.created_at')->title(__('date'))->addClass('text-nowrap'),
            Column::make('reference_deposit')->title(__('reference no')),
            Column::make('amount')->title(__('amount')),
            Column::make('status')->title(__('status')),
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
        return 'Payments_' . date('YmdHis');
    }
}
