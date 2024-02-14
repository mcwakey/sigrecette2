<?php

namespace App\DataTables;

use App\Models\TaxpayerTaxable;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class TaxpayerTaxablesDataTable extends DataTable
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
            ->editColumn('taxpayer_taxable', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._label', compact('taxpayer_taxable'));
            })
            // ->editColumn('tax_type', function (TaxpayerTaxable $taxpayer_taxable) {
            //     return $taxpayer_taxable->;
            // })
            ->editColumn('name', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->name;
            })
            ->editColumn('seize', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._seize', compact('taxpayer_taxable'));
            })
            ->editColumn('status', function (TaxpayerTaxable $taxpayer_taxable) {
                return '';
            })
            ->editColumn('location', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._location', compact('taxpayer_taxable'));
            })
            // ->editColumn('created_at', function (TaxpayerTaxable $taxpayer_taxable) {
            //     return $taxpayer_taxable->created_at->format('d M Y');
            // })
            ->addColumn('action', function (TaxpayerTaxable $taxable) {
                return view('pages.taxpayer_taxables.columns._actions', compact('taxable'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(): QueryBuilder // Remove $request parameter
    {
        return TaxpayerTaxable::where('taxpayer_id', $this->id); // Filter taxpayer_taxables by taxpayer_id
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('taxpayer_taxables-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayer_taxables/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('taxpayer_taxable')->title(__('taxpayer_taxable'))->addClass('text-nowrap'),
            //Column::make('taxpayer_taxable_no')->title(__('taxpayer_taxable no')),
            //Column::make('tax_type')->title(__('tax_type')),
            Column::make('name')->title(__('tax_name')),
            //Column::make('seize')->title(__('amount')),
            Column::make('seize')->title(__('seize'))->addClass('text-nowrap'),
            Column::make('status')->title(__('status')),
            Column::make('location')->title(__('location'))->addClass('text-nowrap'),
            //Column::make('created_at')->title(__('created Date'))->addClass('text-nowrap'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(true)
                ->printable(true)
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
        return 'TaxpayerTaxables_' . date('YmdHis');
    }
}
