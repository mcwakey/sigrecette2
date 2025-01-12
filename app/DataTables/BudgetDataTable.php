<?php
namespace App\DataTables;
use App\Enums\InvoicePayStatusEnums;
use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\Budget;
use App\Models\Invoice;
use App\Models\Payment;
use App\Traits\HandlesTaxpayerFilters;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Yajra\DataTables\WithExportQueue;
class BudgetDataTable extends DataTable
{
    use WithExportQueue;
    use HandlesTaxpayerFilters;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query, Request $request): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('tax_label.name', function (Budget $budget) {
                return $budget->tax_label->name ?? '-';
            })
            ->editColumn('tax_label.code', function (Budget $budget) {
                return $budget->tax_label->code ?? '-';
            })
            ->editColumn('year.name', function (Budget $budget) {
                return $budget->year->name ?? '-';
            })
            ->editColumn('expected_amount', function (Budget $budget) {
                return number_format($budget->expected_amount, 2);
            });
    }
    public function getColumns(): array
    {
        return [
            Column::make('tax_label.name')->title(__(' Tax Label'))->addClass('text-center'),

            Column::make('tax_label.code')->title(__('Code Tax Label'))->addClass('text-center'),
            Column::make('expected_amount')->title(__('Expected Amount'))->addClass('text-end'),
            Column::make('year.name')->title(__('Year'))->addClass('text-center'),

        ];
    }
    public function query(Budget $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['tax_label', 'year'])
            ->select('budgets.*');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('budgets-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->pageLength(100) // Set the default number of rows per page to 3
            ->lengthMenu([[100, 300, 500, -1], [100, 300, 500, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayers/budgets/_draw-scripts.js')) . "}");
    }


    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Budgets_' . date('YmdHis');
    }

}
