<?php

namespace App\DataTables;

use App\Models\Canton;
use App\Models\Erea;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class EreasDataTable extends DataTable
{

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))


            ->editColumn('name', function (Erea $erea) {return $erea->name;})
            ->editColumn('status', function (Erea $erea) {
                return $erea->status;
            })
            ->editColumn('town_id', function (Erea $erea) {return Canton::find($erea->town_id)->name ;})

            ->editColumn('created_at', function (Erea $erea) {
                return $erea->created_at->format('d M Y');
            })
            ->addColumn('action', function (Erea $erea) {
                return view('pages/ereas.columns._actions', compact('erea'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Erea $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('ereas')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/ereas/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
           Column::make('town_id')->title('canton')->name("town_id"),
            Column::make('name'),
            //Column::make('gender')->title('Tax Name'),
            Column::make('status')->title('status'),
            Column::make('created_at')->title('created Date')->addClass('text-nowrap'),
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
        return 'Ereas_' . date('YmdHis');
    }
}
