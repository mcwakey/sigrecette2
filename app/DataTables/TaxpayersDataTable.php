<?php

namespace App\DataTables;

use App\Models\Taxpayer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Button;

class TaxpayersDataTable extends DataTable
{


    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['taxpayer', 'last_login_at'])
            ->editColumn('taxpayer', function (Taxpayer $taxpayer) {
                return view('pages/taxpayers.columns._taxpayer', compact('taxpayer'));
            })
            ->editColumn('gender', function (Taxpayer $taxpayer) {
                return $taxpayer->gender;
            })
            ->editColumn('mobilephone', function (Taxpayer $taxpayer) {
                return view('pages/taxpayers.columns._phone', compact('taxpayer'));
            })
            ->editColumn('canton', function (Taxpayer $taxpayer) {
                return $taxpayer->canton;
            })
            ->editColumn('town', function (Taxpayer $taxpayer) {
                return $taxpayer->town . " - " . $taxpayer->erea;
            })
            ->editColumn('erea', function (Taxpayer $taxpayer) {
                return $taxpayer->erea;
            })
            ->editColumn('address', function (Taxpayer $taxpayer) {
                return $taxpayer->address;
            })
            ->editColumn('zone', function (Taxpayer $taxpayer) {
                return $taxpayer->zone_id;
            })
            ->editColumn('created_at', function (Taxpayer $taxpayer) {
                return $taxpayer->created_at->format('d M Y');
            })
            ->addColumn('action', function (Taxpayer $taxpayer) {
                return view('pages/taxpayers.columns._actions', compact('taxpayer'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Taxpayer $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('taxpayers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(7)
            ->dom("<'d-flex justify-content-end absolute top-0'B>")
            ->buttons([
                'print',
                'excel',
                'csv',
                'pdf',
            ])
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayers/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('taxpayer')->title(__('taxpayer'))->addClass('d-flex align-items-center taxpayer')->name('name'),
            Column::make('gender')->title(__('gender'))->addClass('gender'),
            Column::make('mobilephone')->title(__('mobilephone'))->addClass('d-flex align-items-center mobilephone')->name('mobilephone'),
            Column::make('canton')->title(__('canton'))->addClass('canton'),
            Column::make('town')->title(__('town'))->addClass('twon'),
            Column::make('address')->title(__('address'))->addClass('address'),
            Column::make('zone')->title(__('zone'))->class('zone'),
            Column::make('created_at')->title(__('joined date'))->addClass('text-nowrap created_at'),
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
        return 'Taxpayers_' . date('YmdHis');
    }
}
