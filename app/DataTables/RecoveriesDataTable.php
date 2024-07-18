<?php

namespace App\DataTables;

use App\Enums\PaymentStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Year;
use Carbon\Carbon;
use PHPUnit\TextUI\Configuration\Constant;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\WithExportQueue;

class RecoveriesDataTable extends DataTable
{
    use WithExportQueue;


    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query, Request $request): EloquentDataTable
    {

        return (new EloquentDataTable($query))
        ->editColumn('users.name', function (Payment $payment) {
            return $payment->user->name;
            // $user = $payment->user;
            // return view('pages/apps.user-management.users.columns._user', compact('user'));
            //return view('pages/recoveries.columns._user', compact('user'));
        })
            ->editColumn('invoices.invoice_no', function (Payment $payment) {
                return $payment->invoice->invoice_no;
            })
            ->editColumn('reference', function (Payment $payment) {
                return $payment->reference;
            })
            ->editColumn('tax_labels.code', function (Payment $payment) {
                return $payment->code ?? '';
            })
            ->editColumn('taxpayers.name', function (Payment $payment) {
                //$invoice = $payment->invoice;
                return view('pages/recoveries.columns._invoice', compact('payment'));
            })
            ->editColumn('amount', function (Payment $payment) {
                return format_amount($payment->amount)  ;
            })
            ->editColumn('remaining_amount', function (Payment $payment) {
                return format_amount($payment->remaining_amount);
            })

            ->editColumn('status', function (Payment $payment) {
                //return $payment->remaining_amount;
                return view('pages/recoveries.columns._status', compact('payment'));
            })
            ->addColumn('action', function (Payment $payment) {
                return view('pages/recoveries.columns._actions', compact('payment'));
            })
            ->setRowId('uuid');
    }



    public function query(Payment $model): QueryBuilder
    {
        $query = $model
            ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'payments.taxpayer_id')
            ->leftJoin('users', 'users.id', '=', 'payments.user_id')
            ->join('tax_labels', 'tax_labels.code', '=', 'payments.code')
            ->select('payments.*')
            ->whereNotNull('payments.user_id')
            ->whereNotIn('payments.reference', [Constants::ANNULATION, Constants::REDUCTION])
            ->distinct()
            ->whereBetween('payments.created_at', [$this->startDate, $this->endDate])
            ->orderBy('payments.created_at', 'desc')
            ->newQuery();




        if ($this->state!=null) {
            $query->where('payments.status', '=', $this->state);
        }else {
            $query->whereIn('payments.status', [PaymentStatusEnums::DONE,PaymentStatusEnums::ACCOUNTED]);
        }

        return $query;
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('recoveries-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('recoveries.index',request()->all()))
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(7)
            ->pageLength(100) // Set the default number of rows per page to 3
            ->lengthMenu([[100,300, 500,  -1], [100,300, 500, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/recoveries/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('taxpayers.name')->title(__('taxpayer'))->addClass('d-flex align-items-center'),
            Column::make('invoices.invoice_no')->title(__('invoice no')),
            Column::make('reference')->title(__("reference no"))->name("reference"),
            Column::make('tax_labels.code')->title(__('code')),
            Column::make('amount')->title(__('amount paid')),
            Column::make('status')->title(__('status')),
            Column::make('users.name')->title(__('user'))->addClass('d-flex align-items-center'),
            Column::make('taxpayer_id')->visible(false),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
        $columns = array_map(function ($column) {

            if ($this->state!=PaymentStatusEnums::CANCELED) {
                if (in_array($column->name, ['action'])) {
                    $column->visible(false);
                }
            }
            return $column;
        }, $columns);
        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'payments_' . date('YmdHis');
    }
}
