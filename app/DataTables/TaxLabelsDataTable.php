<?php

namespace App\DataTables;

use App\Models\TaxLabel;
use App\Models\Taxpayer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class TaxLabelsDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            //->rawColumns(['tax_label', 'last_login_at'])
            // ->editColumn('tax_label', function (TaxLabel $tax_label) {
            //     return view('pages/tax_labels.columns._tax_label', compact('tax_label'));
            // })
            ->editColumn('category', function (TaxLabel $tax_label) {
                return __($tax_label->category);
            })
            ->editColumn('name', function (TaxLabel $tax_label) {
                return $tax_label->name;
            })
            ->editColumn('code', function (TaxLabel $tax_label) {
                return $tax_label->code;
            })
            ->editColumn('status', function (TaxLabel $tax_label) {
                return $tax_label->status;
            })
            // ->editColumn('penalty', function (TaxLabel $tax_label) {
            //     return $tax_label->penalty.$tax_label->penalty_type;
            // })
            ->editColumn('created_at', function (TaxLabel $tax_label) {
                return $tax_label->created_at->format('d M Y');
            })
            ->addColumn('action', function (TaxLabel $tax_label) {
                return view('pages/tax_labels.columns._actions', compact('tax_label'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(TaxLabel $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tax_labels-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/tax_labels/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // Column::make('tax_label')->addClass('d-flex align-items-center')->name('name'),
            //Column::make('gender')->title('Tax Name'),
            Column::make('name')->title(__('taxlabel')),
            Column::make('code')->title(__('code')),
            Column::make('category')->title(__('category')),
            Column::make('status')->title(__('status')),
            // Column::make('penalty')->title('penalty'),
            Column::make('created_at')->title(__('created at'))->addClass('text-nowrap'),
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
        return 'TaxLabels_' . date('YmdHis');
    }
}
