<?php

namespace App\DataTables;

use App\Models\Payment;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class LedgersDataTable extends DataTable
{

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
            
            ->editColumn('payments.created_at', function (Payment $payment) {
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
            // Add the amount of the current row to the accumulated amount
            $newAmount += $payment->amount;
            
            // Return the accumulated amount
            return $newAmount;
        })

            ->editColumn('stock_transfers.code', function (Payment $payment) {
                return $payment->stock_transfers->first()->code ?? $payment->code;
                //return $payment->code;
            })
            ->addColumn('action', function (Payment $payment) {
                return view('pages.ledgers.columns._actions', compact('payment'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(): QueryBuilder // Remove $request parameter
    public function query(Payment $model): QueryBuilder
    {
        // return $model->join('taxables', 'payments.taxable_id', '=', 'taxables.id')
        //             // ->with('taxable.tax_label')
        //             ->join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
        //             ->join('users', 'payments.to_user_id', '=', 'users.id')
        //             ->where('payments.trans_type', 'VENDU') // Filter collector_deposits by taxpayer_id
        //             ->select('payments.*')
        //             //->orderBy('tax_labels.name')
        //             ->newQuery();

        return $model
        // ->join('taxables', 'payments.taxable_id', '=', 'taxables.id')
                    // ->with('taxable.tax_label')
                    // ->join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
                    // ->join('users', 'payments.to_user_id', '=', 'users.id')
                     ->where('status', 'APROVED') // Filter collector_deposits by taxpayer_id
                    //  ->orWhere('taxpayer_id', null) // Filter collector_deposits by taxpayer_id
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
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/ledgers/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title(__('id'))->exportable(false)->printable(false)->visible(false), 
            Column::make('payments.created_at')->title(__('date'))->addClass('text-nowrap'),
            //Column::make('trans_desc')->title(__('trans_desc')),
            Column::make('description')->title(__('description')),
            Column::make('stock_transfers.code')->title(__('code')),
            Column::make('reference')->title(__('reference no')),
            //Column::make('tariff')->title(__('tariff'))->addClass('text-nowrap')->name('tax_labels.name'),
            Column::make('amount')->title(__('amount')),
            //Column::make('tax_type')->title(__('tax_type')),
            //Column::make('qty')->title(__('rc qty'))->addClass('text-nowrap'),
            Column::make('deposit')->title(__('versement')),
            Column::make('newAmount')->title(__('solde')),
            // Column::make('total')->title(__('vv total'))->name('qty'),
            //Column::make('qty2')->title(__('rd qty'))->addClass('text-nowrap'),
            //Column::make('total2')->title(__('rd total')),
            // Column::make('qty2')->title(__('sd qty'))->addClass('text-nowrap'),
            // // Column::make('total2')->title(__('sd total')),
            // Column::make('tax_labels.code')->title(__('code')),
            // Column::make('users.name')->title(__('collector')),
            // Column::make('payments.type')->title(__('status')),
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
