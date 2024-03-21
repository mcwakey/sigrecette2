<?php

namespace App\DataTables;

use App\Models\Year;
use App\Models\Invoice;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class YearDataTable extends DataTable
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
            ->editColumn('name', function (Year $year) {
                return $year->name;
            })
            ->editColumn('status', function (Year $year) {
                // return $year->status;
                // return sprintf('<div class="badge badge-light fw-bold">%s</div>', $year->status);
                return view('pages/years.columns._status', compact('year'));
            })

            ->editColumn('created_at', function (Year $year) {
                return $year->created_at ? $year->created_at->format('d M Y') : null;
            })
            ->addColumn('action', function (Year $year) {
                return view('pages/years.columns._actions', compact('year'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Year $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('years')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/years/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('name')->title(__('year')),
            //Column::make('gender')->title('Tax Name'),
            Column::make('status')->title(__('status'))->width(150),
            Column::make('created_at')->title(__('created at'))->addClass('text-nowrap')->width(150),
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
        return 'Years_' . date('YmdHis');
    }
}
