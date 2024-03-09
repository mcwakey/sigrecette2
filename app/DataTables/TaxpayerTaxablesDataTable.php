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
            ->filter(function ($query) {
                if (request()->filled('search.value')) {
                    $query->where('tax_labels.name', 'like', '%' . request('search.value') . '%')
                    ->orWhere('taxables.name', 'like', '%' . request('search.value') . '%')
                    ->orWhere('tax_labels.code', 'like', '%' . request('search.value') . '%');
                    // Add additional search conditions as needed for other columns
                }
            })
            ->rawColumns(['status'])
            ->editColumn('id', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->id;
            })
            ->editColumn('billable', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._bill', compact('taxpayer_taxable'));
            })
            ->editColumn('name', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->name;
            })
            ->editColumn('taxpayer_taxable', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._label', compact('taxpayer_taxable'));
            })
            // ->editColumn('tax_type', function (TaxpayerTaxable $taxpayer_taxable) {
            //     return $taxpayer_taxable->;
            // })
            ->editColumn('seize', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->seize. " ". $taxpayer_taxable->taxable->unit;
                // return view('pages.taxpayer_taxables.columns._seize', compact('taxpayer_taxable'));
            })
            ->editColumn('status', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._status', compact('taxpayer_taxable'));
            })
            // ->editColumn('location', function (TaxpayerTaxable $taxpayer_taxable) {
            //     return view('pages.taxpayer_taxables.columns._location', compact('taxpayer_taxable'));
            // })
            ->editColumn('created_at', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->created_at->format('d M Y');
            })
            ->addColumn('action', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._actions', compact('taxpayer_taxable'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(): QueryBuilder // Remove $request parameter
    public function query(TaxpayerTaxable $model): QueryBuilder
    {
        return $model->with('taxable')
                    ->join('taxables', 'taxpayer_taxables.taxable_id', '=', 'taxables.id')
                    ->with('taxable.tax_label')
                    ->join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
                    ->where('taxpayer_taxables.taxpayer_id', $this->id) // Filter taxpayer_taxables by taxpayer_id
                    ->select('taxpayer_taxables.*')
                    //->orderBy('tax_labels.name')
                    ->newQuery();

        // return TaxpayerTaxable::where('taxpayer_id', $this->id); // Filter taxpayer_taxables by taxpayer_id
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
            ->orderBy(5)
            ->pageLength(3) // Set the default number of rows per page to 3
            ->lengthMenu([[3, 10, 25, 50, -1], [3, 10, 25, 50, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayer_taxables/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            //Column::make('id')->title(__('id'))->exportable(false)->printable(false)->visible(false), 
            Column::make('billable')->title(__('empty'))->addClass('text-nowrap'),
            Column::make('name')->title(__('asset name'))->width(600),
            Column::make('taxpayer_taxable')->title(__('taxable'))->addClass('text-nowrap')->name('tax_labels.name'),
            Column::make('taxable.name')->title(__('empty'))->visible(false),
            //Column::make('tax_type')->title(__('tax_type')),
            //Column::make('seize')->title(__('amount')),
            Column::make('seize')->title(__('seize'))->addClass('text-nowrap'),
            Column::make('status')->title(__('status')),
            //Column::make('location')->title(__('location'))->addClass('text-nowrap'),
            Column::make('created_at')->title(__('created at'))->addClass('text-nowrap')->width(150),
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
        return 'TaxpayerTaxables_' . date('YmdHis');
    }
}
