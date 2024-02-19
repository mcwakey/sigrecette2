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
            ->editColumn('id', function (Taxpayer $taxpayer) {
                return $taxpayer->id;
            })
            ->editColumn('taxpayer', function (Taxpayer $taxpayer) {
                return view('pages/taxpayers.columns._taxpayer', compact('taxpayer'));
            })
            ->editColumn('gender', function (Taxpayer $taxpayer) {
                return $taxpayer->gender;
            })
            ->editColumn('mobilephone', function (Taxpayer $taxpayer) {
                return view('pages/taxpayers.columns._phone', compact('taxpayer'));
            })
            ->editColumn('canton_id', function (Taxpayer $taxpayer) {
                return $taxpayer->town->canton->name;
            })
            ->editColumn('town_id', function (Taxpayer $taxpayer) {
                return $taxpayer->town->name;
            })
            ->editColumn('erea_id', function (Taxpayer $taxpayer) {
                return $taxpayer->erea->name;
            })
            ->editColumn('address', function (Taxpayer $taxpayer) {
                return $taxpayer->address;
            })
            ->editColumn('zone_id', function (Taxpayer $taxpayer) {
                return $taxpayer->zone->name;
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
            //->dom("") // Add pagination ('p') and other controls ('i') at the bottom
            ->dom("<'d-flex justify-content-end'B> ".'rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
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
            Column::make('id')->title(__('id'))->visible(false),
            Column::make('taxpayer')->title(__('taxpayer'))->addClass('d-flex align-items-center')->name('name'),
            Column::make('gender')->title(__('gender')),
            Column::make('mobilephone')->title(__('mobilephone'))->addClass('text-nowrap')->name('mobilephone'),
            Column::make('canton_id')->title(__('canton'))->name('name'),
            Column::make('town_id')->title(__('town')),
            Column::make('erea_id')->title(__('erea')),
            Column::make('address')->title(__('address')),
            Column::make('zone_id')->title(__('zone')),
            //Column::make('created_at')->title(__('joined date'))->addClass('text-nowrap created_at')->visible(false),
            Column::computed('action')
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
        return 'Taxpayers_' . date('YmdHis');
    }
}
