<?php

namespace App\DataTables;

use App\Models\Activity;
use App\Models\Taxpayer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class ActivityDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('name', function (Activity $activity) {
                return $activity->name;
            })
            ->editColumn('category_id', function (Activity $activity) {
                return $activity->category->name;
            })
            ->editColumn('status', function (Activity $activity) {
                return view('pages/activities.columns._status', compact('activity'));
            })
            ->editColumn('created_at', function (Activity $activity) {
                return $activity->created_at->format('d M Y');
            })
            ->addColumn('action', function (Activity $activity) {
                return view('pages/activities.columns._actions', compact('activity'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Activity $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('activities')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(3)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/activities/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // Column::make('taxable')->addClass('d-flex align-items-center')->name('name'),
            //Column::make('gender')->title('Tax Name'),
            Column::make('name')->title(__('activity')),
            Column::make('category_id')->title(__('category'))->width(400),
            // Column::make('periodicity')->title('periodicity'),
            // Column::make('modality')->title('modality'),
            // Column::make('penalty')->title('penalty'),
            Column::make('status')->title(__(__('status')))->width(150),
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
        return 'activities_' . date('YmdHis');
    }
}
