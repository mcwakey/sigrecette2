<?php

namespace App\DataTables;

use App\Helpers\Constants;
use App\Models\Payment;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class AccountantDepositsOutrightDataTable extends DataTable
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
            ->editColumn('invoice_no', function (Payment $payment) {
                return $payment->invoice->invoice_no ?? '';
            })
            ->editColumn('order_no', function (Payment $payment) {
                return $payment->invoice->order_no ?? '';
            })
            ->editColumn('reference', function (Payment $payment) {
                return $payment->reference;
            })
            ->editColumn('amount', function (Payment $payment) {
                return $payment->amount;
            })
            ->editColumn('stock_transfers.code', function (Payment $payment) {
                return $payment->stock_transfers->first()->code ?? '';
                //return $payment->code;
            })
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
            // ->editColumn('payments.type', function (Payment $payment) {
            //     return view('pages.payments.columns._status', compact('payment'));
            //     //return $stock_request->type;
            // })
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
        return $model
        // ->join('taxables', 'payments.taxable_id', '=', 'taxables.id')
                    // ->with('taxable.tax_label')
                    // ->join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
                    // ->join('users', 'payments.to_user_id', '=', 'users.id')
                    //  ->orWhere('invoice_id', null) // Filter collector_deposits by taxpayer_id
                    ->where('invoice_type', Constants::INVOICE_TYPE_COMPTANT) // Filter collector_deposits by taxpayer_id
                    ->where('status', 'APROVED') // Filter collector_deposits by taxpayer_id
                    // ->select('payments.*')
                    //->orderBy('tax_labels.name')
                    ->newQuery();

        // return Payment::where('taxpayer_id', $this->id); // Filter collector_deposits by taxpayer_id
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
            Column::make('description')->title(__('description')),
            Column::make('reference')->title(__('reference no')),
            Column::make('amount')->title(__('amount')),
            Column::make('code')->title(__('code')),
            Column::make('order_no')->title(__('order no')),
            //Column::make('tariff')->title(__('tariff'))->addClass('text-nowrap')->name('tax_labels.name'),
            //Column::make('tax_type')->title(__('tax_type')),
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
            // // Column::make('payments.type')->title(__('status')),
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
