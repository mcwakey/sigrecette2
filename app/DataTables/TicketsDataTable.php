<?php
namespace App\DataTables;
use App\Models\Taxable;
use App\Models\Taxpayer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Log;
class TicketsDataTable extends DataTable
{
    public $query = false;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('name', function (Taxable $taxable) {
                return $taxable->name;
            })
            ->editColumn('tariff', function (Taxable $taxable) {
                return $taxable->tariff;
            })
            ->editColumn('unit_type', function (Taxable $taxable) {
                return $taxable->unit_type;
            })
            ->editColumn('unit', function (Taxable $taxable) {
                return $taxable->unit;
            })
            ->editColumn('created_at', function (Taxable $taxable) {
                return $taxable->created_at->format('d M Y');
            })
            ->addColumn('action', function (Taxable $taxable) {
                return view('pages/tickets.columns._actions', ['taxable' => $taxable]);
            })
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(Taxable $model): QueryBuilder
    {
        return $model //->with('tax_label')
        ->where('tax_label_id', '=', null)
            ->newQuery();
    }
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('tickets-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/tickets/columns/_draw-scripts.js')) . "}");
    }
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('unit')->title(__('type')),
            Column::make('name')->title(__('ticket')),
            Column::make('tariff')->title(__('tariff')),
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
        return 'Tickets_' . date('YmdHis');
    }
}
