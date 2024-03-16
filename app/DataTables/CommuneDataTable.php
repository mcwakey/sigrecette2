<?php

namespace App\DataTables;

use App\Models\Commune;
use App\Models\Taxpayer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class CommuneDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('title', function (Commune $commune) {
                return $commune->title;
            })
            ->editColumn('name', function (Commune $commune) {
                return $commune->name;
            })
            ->editColumn('region_name', function (Commune $commune) {
                return $commune->region_name;
            })
            ->editColumn('mayor_name', function (Commune $commune) {
                return $commune->mayor_name;
            })
            ->editColumn('phone_number', function (Commune $commune) {
                return $commune->phone_number;
            })
            ->editColumn('address', function (Commune $commune) {
                return $commune->address;            })
            ->editColumn('treasury_name', function (Commune $commune) {
                return $commune->treasury_name;            })
            ->editColumn('treasury_address', function (Commune $commune) {
                return $commune->treasury_address;            })
            ->editColumn('treasury_rib', function (Commune $commune) {
                return $commune->treasury_rib;            })
            ->editColumn('created_at', function (Commune $commune) {
                return $commune->created_at->format('d M Y');
            })
            ->addColumn('action', function (Commune $commune) {
                return view('pages/communes.columns._actions', compact('commune'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Commune $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('communes')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/communes/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->title(__('commune_name')),
            Column::make('title')->title(__('commune_title')),
            Column::make('region_name')->title(__('region_name')),
            Column::make('mayor_name')->title(__('mayor_name')),
            Column::make('phone_number')->title(__('phone_number')),
            Column::make('address')->title(__(__('address'))),
            Column::make('treasury_name')->title(__(__('treasury_name'))),
            Column::make('treasury_address')->title(__(__('treasury_address'))),
            Column::make('treasury_rib')->title(__(__('treasury_rib'))),

            Column::make('created_at')->title(__('created at'))->addClass('text-nowrap')->width(150),
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
        return 'Communes_' . date('YmdHis');
    }
}
