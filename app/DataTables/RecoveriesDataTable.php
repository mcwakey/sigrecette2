<?php

namespace App\DataTables;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Year;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class RecoveriesDataTable extends DataTable
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
        ->editColumn('user.name', function (Payment $payment) {
            return $payment->user->name;
            // $user = $payment->user;
            // return view('pages/apps.user-management.users.columns._user', compact('user'));
            //return view('pages/recoveries.columns._user', compact('user'));
        })
            ->editColumn('no_avis', function (Payment $payment) {
                return $payment->invoice->invoice_no;
            })
            ->editColumn('reference', function (Payment $payment) {
                return $payment->reference;
            })
            ->editColumn('tax_labels.id', function (Payment $payment) {
                return $payment->code ?? '';
            })
            ->editColumn('nic', function (Payment $payment) {
                return $payment->invoice->nic;
            })
            ->editColumn('zones.name', function (Payment $payment) {
                return  $payment->invoice->taxpayer->zone->name ?? '-';
            })
            ->editColumn('taxpayers.address', function (Payment $payment) {
                return $payment->invoice->taxpayer->address ?? '-';
            })
            ->editColumn('taxpayers.latitude', function (Payment $payment) {
                return ($payment->invoice->taxpayer->latitude ?? '-').' : '.($payment->invoice->taxpayer->longitude ?? '-');
            })
            ->editColumn('taxpayers.name', function (Payment $payment) {
                //$invoice = $payment->invoice;
                return view('pages/recoveries.columns._invoice', compact('payment'));
            })
            ->editColumn('amount', function (Payment $payment) {
                return $payment->amount;
            })
            ->editColumn('remaining_amount', function (Payment $payment) {
                return $payment->remaining_amount;
            })
            ->editColumn('status', function (Payment $payment) {
                //return $payment->remaining_amount;
                return view('pages/recoveries.columns._status', compact('payment'));
            })
            ->addColumn('action', function (Payment $payment) {
                return view('pages/recoveries.columns._actions', compact('payment'));
            })
            ->setRowId('id');
    }


    public function query(Payment $model): QueryBuilder
    {
        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");

        return $model->newQuery()
            ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->whereNotNull('payments.user_id')
            ->whereBetween('payments.created_at', [$startOfYear, $endOfYear])
            ->select('payments.*');
    }


    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('recoveries-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(8)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/recoveries/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('taxpayers.name')->title(__('taxpayer'))->addClass('d-flex align-items-center'),
            Column::make('no_avis')->title(__('invoice no'))->name('no_avis'),
            Column::make('reference')->title(__("reference no"))->name("reference"),
            Column::make('tax_labels.id')->title(__('taxlabel')),
            Column::make('nic')->title(__('nic')),
            Column::make('taxpayers.latitude')->title(__('gps')),
            Column::make('taxpayers.address')->title(__('address')),
            Column::make('amount')->title(__('amount paid')),
            Column::make('remaining_amount')->title(__('balance')),
            Column::make('status')->title(__('status')),
            Column::make('user.name')->title(__('user'))->addClass('d-flex align-items-center'),
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
        return 'payments_' . date('YmdHis');
    }
}
