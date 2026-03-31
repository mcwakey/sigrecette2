<?php

namespace App\DataTables;

use App\Enums\PaymentStatusEnums;
use App\Enums\PaymentTypeEnums;
use App\Helpers\Constants;
use App\Models\Payment;
use App\Models\Year;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\WithExportQueue;

class LedgersDataTable extends DataTable
{
    use WithExportQueue;

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $newAmount = 0;
        return (new EloquentDataTable($query))
            ->rawColumns(['status'])
            ->editColumn('created_at', function (Payment $payment) {
                return $payment->created_at->format('d M Y');
            })
            ->editColumn('description', function (Payment $payment) {
                return $payment->description;
            })
            ->editColumn('reference', function (Payment $payment) {
                return $payment->reference;
            })
            ->editColumn('amount', function (Payment $payment) {
                return $payment->amount;
            })
            ->editColumn('newAmount', function (Payment $payment) use (&$newAmount) {

                $newAmount += $payment->amount - $payment->deposit;
                return $newAmount;
            })
            ->editColumn('code', function (Payment $payment) {
                return $payment->stock_transfers->first()->code ?? $payment->code;
            })
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(Payment $model): QueryBuilder
    {

        return $model
            ->whereNot('status', PaymentStatusEnums::PENDING->value) // Filter collector_deposits by taxpayer_id
            ->whereBetween('payments.created_at', [$this->startDate, $this->endDate])
            ->orderBy('created_at', 'asc')
            ->newQuery();
    }
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        $columns = $this->getColumns();
        return $this->builder()
            ->setTableId('ledgers-table')
            ->columns($columns)
            ->minifiedAjax()
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            ->pageLength(500)
            ->lengthMenu([[500, -1], [500, "All"]]) // Define options for the number of rows per page
            //->drawCallback("function() {" . file_get_contents(resource_path('views/pages/ledgers/columns/_draw-scripts.js')) . "}")
            ;
    }
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title(__('id'))->exportable(false)->printable(false)->visible(false),
            Column::make('created_at')->title(__('date'))->addClass('text-nowrap'),
            Column::make('description')->title(__('description')),
            Column::make('code')->title(__('code')),
            Column::make('reference')->title(__('reference no')),
            Column::make('amount')->title(__('amount')),
            Column::make('deposit')->title(__('versement')),
            Column::make('newAmount')->title(__('solde')),
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
