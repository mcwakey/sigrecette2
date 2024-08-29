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
            // ->editColumn('id', function (Payment $payment) {
            //     return $payment->id;
            // })
            // ->editColumn('trans_no', function (Payment $payment) {
            //     return view('pages.collector_deposits.columns._bill', compact('collector_deposit'));
            // })
            ->editColumn('payments.created_at', function (Payment $payment) {
                return $payment->created_at->format('d M Y');
            })
            // ->editColumn('invoice_no', function (Payment $payment) {
            //     return $payment->invoice->invoice_no ?? '';
            // })
            // ->editColumn('order_no', function (Payment $payment) {
            //     return $payment->invoice->order_no ?? '';
            // })
            // ->editColumn('reference', function (Payment $payment) {
            //     return $payment->reference;
            // })
            // ->editColumn('amount', function (Payment $payment) {
            //     return $payment->amount;
            // })
            // ->editColumn('stock_transfers.code', function (Payment $payment) {
            //     return $payment->stock_transfers->first()->code ?? '';
            //     //return $payment->code;
            // })
            // ->editColumn('tariff', function (Payment $payment) {
            //     return $payment->taxable->tariff;
            // })
            // ->editColumn('collector_deposit', function (Payment $payment) {
            //     return view('pages.collector_deposits.columns._label', compact('collector_deposit'));
            // })
            // ->editColumn('tax_type', function (Payment $payment) {
            //     return $payment->;
            // })
            // ->editColumn('payments.start_no', function (Payment $payment) {
            //     return $payment->start_no. " - ". $payment->end_no;
            //     // return view('pages.collector_deposits.columns._seize', compact('collector_deposit'));
            // })
            // ->editColumn('qty', function (Payment $payment) {
            //     if ($payment->trans_type == "RECU") {
            //         $qty = $payment->qty ;
            //     } else {
            //         $qty =  "";
            //     }

            //     return $qty;
            // })
            // ->editColumn('total', function (Payment $payment) {
            //     if ($payment->trans_type == "RECU") {
            //         $total = $payment->qty * $payment->taxable->tariff; ;
            //     } else {
            //         $total =  "";
            //     }

            //     return $total;
            // })
            // ->editColumn('qty', function (Payment $payment) {
            //     if ($payment->trans_type == "VENDU") {
            //         $qty = $payment->qty ;
            //     } else {
            //         $qty =  "";
            //     }

            //     return $qty;
            // })
            // ->editColumn('total', function (Payment $payment) {
            //     if ($payment->trans_type == "VENDU") {
            //         $total = $payment->qty * $payment->taxable->tariff; ;
            //     } else {
            //         $total =  "";
            //     }

            //     return $total;
            // })
            // ->editColumn('qty2', function (Payment $payment) {
            //     if ($payment->trans_type == "RENDU") {
            //         $qty = $payment->qty ;
            //     } else {
            //         $qty =  "";
            //     }

            //     return $qty;
            // })
            // ->editColumn('total2', function (Payment $payment) {
            //     if ($payment->trans_type == "RENDU") {
            //         $total = $payment->qty * $payment->taxable->tariff; ;
            //     } else {
            //         $total =  "";
            //     }

            //     return $total;
            // })
            // ->editColumn('qty2', function (Payment $payment) {
            //     return $payment->qty;
            // })
            // ->editColumn('total2', function (Payment $payment) {
            //     return $payment->qty * $payment->taxable->tariff;
            // })
            // ->editColumn('bill_status', function (Payment $payment) {
            //     return view('pages.collector_deposits.columns._status', compact('collector_deposit'));
            // })
            // ->editColumn('location', function (Payment $payment) {
            //     return view('pages.collector_deposits.columns._location', compact('collector_deposit'));
            // })
            // ->editColumn('users.name', function (Payment $payment) {
            //     return $payment->user->name;
            // })
            ->editColumn('reference_deposit', function (Payment $payment) {
                return view('pages.accountant_deposits.columns._reference', compact('payment'));
            })
            ->editColumn('status', function (Payment $payment) {
                return view('pages.accountant_deposits.columns._status', compact('payment'));
            })
            ->addColumn('action', function (Payment $payment) {
                return view('pages.accountant_deposits.columns._actions', compact('payment'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(): QueryBuilder // Remove $request parameter
    public function query(Payment $model): QueryBuilder
    {
        // return $model
        // // ->join('taxables', 'payments.taxable_id', '=', 'taxables.id')
        //             // ->with('taxable.tax_label')
        //             // ->join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
        //             // ->join('users', 'payments.to_user_id', '=', 'users.id')
        //             //  ->orWhere('invoice_id', null) // Filter collector_deposits by taxpayer_id
        //             // ->where('invoice_type', 'TITRE') // Filter collector_deposits by taxpayer_id
        //             ->where('status',PaymentStatusEnums::ACCOUNTED) // Filter collector_deposits by taxpayer_id
        //             // ->select('payments.*')
        //             //->orderBy('tax_labels.name')
        //             ->newQuery();

        // // return Payment::where('taxpayer_id', $this->id); // Filter collector_deposits by taxpayer_id

        return $model
        // ->join('taxables', 'stock_requests.taxable_id', '=', 'taxables.id')
        // ->join('users', 'stock_requests.user_id', '=', 'users.id')
        ->select('id',
                 DB::raw('SUM(amount) AS amount'),
                //  DB::raw('SUM(CASE WHEN req_type = "VENDU" THEN qty*tariff END) AS vd_qty'),
                //  DB::raw('SUM(CASE WHEN req_type = "RENDU" THEN qty*tariff END) AS rd_qty'),
                 DB::raw('MAX(reference_deposit) AS reference_deposit'),
                //  DB::raw('MAX(stock_requests.req_no) AS req_no'),
                //  DB::raw('MAX(stock_requests.req_desc) AS req_desc'),
                //  DB::raw('MAX(stock_requests.start_no) AS start_no'),
                //  DB::raw('MAX(stock_requests.end_no) AS end_no'),
                //  DB::raw('MAX(stock_requests.last_no) AS last_no'),
                //  DB::raw('MIN(stock_requests.req_type) AS req_type'),
                //  DB::raw('MIN(stock_requests.type) AS type'),
                 DB::raw('MAX(status) AS status'),
                 DB::raw('MAX(payments.updated_at) AS created_at')
                //  DB::raw('MAX(stock_requests.taxable_id) AS taxable_id')
                )
            ->where('status', '!=', PaymentStatusEnums::CANCELED)

        // ->where('status',PaymentStatusEnums::ACCOUNTED) // Filter collector_deposits by taxpayer_id
        // ->groupBy('reference_deposit', 'status')
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
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            //->pageLength(3) // Set the default number of rows per page to 3
            //->lengthMenu([[3, 10, 25, 50, -1], [3, 10, 25, 50, "All"]]) // Define options for the number of rows per page
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
            // Column::make('description')->title(__('description')),
            // Column::make('invoice_no')->title(__('invoice no')),
            // Column::make('order_no')->title(__('order no')),
            Column::make('reference_deposit')->title(__('reference no')),
            //Column::make('tariff')->title(__('tariff'))->addClass('text-nowrap')->name('tax_labels.name'),
            Column::make('amount')->title(__('amount')),
            // Column::make('invoice_type')->title(__('invoice_type')),
            // Column::make('stock_transfers.code')->title(__('code')),
            // //Column::make('qty')->title(__('rc qty'))->addClass('text-nowrap'),
            // Column::make('r_user_id')->title(__('Date')),
            // Column::make('r_user_id')->title(__('No d\'ordre')),
            // // Column::make('total')->title(__('vv total'))->name('qty'),
            // //Column::make('qty2')->title(__('rd qty'))->addClass('text-nowrap'),
            // //Column::make('total2')->title(__('rd total')),
            // // Column::make('qty2')->title(__('sd qty'))->addClass('text-nowrap'),
            // // // Column::make('total2')->title(__('sd total')),
            // // Column::make('tax_labels.code')->title(__('code')),
            // // Column::make('users.name')->title(__('collector')),
            Column::make('status')->title(__('status')),
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
        return 'Payments_' . date('YmdHis');
    }
}
