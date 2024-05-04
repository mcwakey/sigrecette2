<?php

namespace App\DataTables;

use App\Enums\PaymentStatusEnums;
use App\Enums\PaymentTypeEnums;
use App\Helpers\Constants;
use App\Models\PrintFile;
use App\Models\Year;
use Carbon\Carbon;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class PrintablesDataTable extends DataTable
{

    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {

    $newAmount = 0;

        return (new EloquentDataTable($query))

            ->editColumn('created_at', function (PrintFile $printFile) {
                return  $printFile->created_at->format('d M Y');
            })
            ->editColumn('name', function (PrintFile  $printFile) {
                return  $printFile->name;
            })

            ->addColumn('action', function (PrintFile  $printFile) {
                return view('pages.printables.columns._actions', compact('printFile'));
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(): QueryBuilder // Remove $request parameter
    public function query(PrintFile $model): QueryBuilder
    {
        $activeYear = Year::getActiveYear();
        $startOfYear = Carbon::parse("{$activeYear->name}-01-01 00:00:00");
        $endOfYear = Carbon::parse("{$activeYear->name}-12-31 23:59:59");


        return $model
            ->whereBetween('print_files.created_at', [$startOfYear, $endOfYear])

            ->orderBy('created_at','desc' )
                    ->newQuery();

    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('printables-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0, 'desc')
            ->pageLength(100) // Set the default number of rows per page to 3
            ->lengthMenu([[100,300, 500,  -1], [100,300, 500, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/printables/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id')->title(__('id'))->exportable(false)->printable(false)->visible(false),
            Column::make('created_at')->title(__('date')),
            Column::make('last_sequence_number')->title(__('Numéro')),
            Column::make('name')->title(__('description')),
            Column::computed('action')->title(__('action'))
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
        return 'Printables_' . date('YmdHis');
    }
}
