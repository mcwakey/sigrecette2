<?php

namespace App\DataTables;

use App\Models\Taxable;
use App\Models\Taxpayer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class TaxablesDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['taxable', 'last_login_at'])
            // ->editColumn('name', function (Taxable $taxable) {
            //     return $taxable->name;
            // })
            ->editColumn('taxable', function (Taxable $taxable) {
                return view('pages/taxables.columns._taxable', compact('taxable'));
            })
            ->editColumn('tariff', function (Taxable $taxable) {
                return $taxable->tariff;
            })
            ->editColumn('tariff_type', function (Taxable $taxable) {
                return $taxable->tariff_type;
            })
            ->editColumn('unit', function (Taxable $taxable) {
                return $taxable->unit;
            })
            // ->editColumn('modality', function (Taxable $taxable) {
            //     return $taxable->modality;
            // })
            ->editColumn('periodicity', function (Taxable $taxable) {
                return $taxable->periodicity;
            })
            // ->editColumn('penalty', function (Taxable $taxable) {
            //     return $taxable->penalty.$taxable->penalty_type;
            // })
            ->editColumn('created_at', function (Taxable $taxable) {
                return $taxable->created_at->format('d M Y');
            })
            ->addColumn('action', function (Taxable $taxable) {
                return view('pages/taxables.columns._actions', compact('taxable'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Taxable $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('taxables-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(5)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxables/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('taxable')->addClass('d-flex align-items-center')->name('name')->title(__('taxable')),
            //Column::make('name')->title(__('taxable')),
            //Column::make('gender')->title('Tax Name'),
            Column::make('tariff')->title(__('tariff')),
            Column::make('tariff_type')->title(__('tariff type')),
            Column::make('unit')->title(__('unit')),
            Column::make('periodicity')->title(__('periodicity')),
            // Column::make('modality')->title(__('modality')),
            // Column::make('penalty')->title(__('penalty')),
            Column::make('created_at')->title(__('created at'))->addClass('text-nowrap'),
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
        return 'Taxables_' . date('YmdHis');
    }
}
