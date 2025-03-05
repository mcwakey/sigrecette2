<?php
namespace App\DataTables;
use App\Enums\InvoiceStatusEnums;
use App\Helpers\Constants;
use App\Models\Invoice;
use App\Models\Taxpayer;
use App\Models\TaxpayerTaxable;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\WithExportQueue;

class ExportTaxpayerTaxablesDataTable extends DataTable
{
    use WithExportQueue;
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filter(function ($query) {
                if (request()->filled('search.value')) {
                    $query->where('tax_labels.name', 'like', '%' . request('search.value') . '%')
                        ->orWhere('taxables.name', 'like', '%' . request('search.value') . '%')
                        ->orWhere('tax_labels.code', 'like', '%' . request('search.value') . '%')
                        ->orWhere('bill_status', 'like', '%' . request('search.value') . '%');
                    // Add additional search conditions as needed for other columns
                }
            })
            ->rawColumns(['status'])
            ->editColumn('taxpayer.name', function (TaxpayerTaxable $taxpayerTaxable) {
                return $taxpayerTaxable->taxpayer?->name;
            })
            ->editColumn('width', function (TaxpayerTaxable $taxpayer_taxable) {
                if ($taxpayer_taxable->taxable->unit_type=='Superficie'){
                    return $taxpayer_taxable->width;
                }
                return "";
            })
            ->editColumn('length', function (TaxpayerTaxable $taxpayer_taxable) {
                if ($taxpayer_taxable->taxable->unit_type=='Superficie'){
                    return $taxpayer_taxable->length;
                }
                return "";
            })
            ->editColumn('id', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->id;
            })
            ->editColumn('billable', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._bill', ['taxpayer_taxable' => $taxpayer_taxable]);
            })
            ->editColumn('name', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->name;
            })
            ->editColumn('tariff', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->taxable->tariff;
            })
            ->editColumn('taxpayer_taxable', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->taxable->name;
            })
            ->editColumn('taxable.tax_label.code', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->taxable->tax_label?->code;
            })
            ->editColumn('seize', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->seize . " " . $taxpayer_taxable->taxable->unit;
            })
            ->editColumn('e_amount', function (TaxpayerTaxable $taxpayer_taxable) {
                if ($taxpayer_taxable->taxable->periodicity == "Mois") {
                    $period = 1;
                } elseif ($taxpayer_taxable->taxable->periodicity == "Ans") {
                    $period = 0.083333;
                } else {
                    $period = 1;
                }
                $amount=0;
                if( is_numeric($taxpayer_taxable->seize)){
                    if ($taxpayer_taxable->taxable->tariff_type == "FIXED") {
                        $amount = floatval($taxpayer_taxable->taxable->tariff) * ($taxpayer_taxable->taxable->use_second_formula == true ? 1 : floatval($taxpayer_taxable->seize) * $period);

                    } else {
                        $amount=  floatval($taxpayer_taxable->taxable->tariff) * ($taxpayer_taxable->taxable->use_second_formula == true ? 1 : floatval($taxpayer_taxable->seize) * $period/100);
                        // $amount=2;
                    }
                 //  dd(floatval($taxpayer_taxable->taxable->tariff), $taxpayer_taxable->taxable->use_second_formula,floatval($taxpayer_taxable->seize), $period, $amount);
                }

                return format_amount(round($amount, 2));
                //return round($amount, 2);
            })
            ->editColumn('bill_status', function (TaxpayerTaxable $taxpayer_taxable) {
                return view('pages.taxpayer_taxables.columns._status', ['taxpayer_taxable' => $taxpayer_taxable]);
            })
            ->editColumn('created_at', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->created_at->format('d M Y');
            })
            ->editColumn('invoice_id', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->invoice_id;
            })
            ->editColumn('invoice.type', function (TaxpayerTaxable $taxpayer_taxable) {
                return $taxpayer_taxable->invoice?->type;
            })
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(TaxpayerTaxable $model): QueryBuilder
    {
        return $model->with(['taxable', 'taxpayer','invoice','taxable.tax_label'])
            ->join('taxables', 'taxpayer_taxables.taxable_id', '=', 'taxables.id')
            ->leftJoin('taxpayers', 'taxpayers.id', '=', 'taxpayer_taxables.taxpayer_id')
            ->where('taxpayers.type','=',Constants::TITRE)
            ->join('tax_labels', 'taxables.tax_label_id', '=', 'tax_labels.id')
            ->select('taxpayer_taxables.*')
            ->whereBetween('taxpayer_taxables.created_at', [$this->startDate, $this->endDate])

           // ->where(function ($query) {$query->whereNull('invoices.id')->orWhere('invoices.type', 'TITRE');})
            ->newQuery();
    }
    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('export-taxpayer_taxables-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(1)
            ->pageLength(100)
            ->lengthMenu([[100, 300, 500, -1], [100, 300, 500, "All"]]) // Define options for the number of rows per page
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayer_taxables/columns/_draw-scripts.js')) . "}");
    }
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('taxpayer_id'),
            Column::make('taxpayer.name')->title(__('taxpayer')),
            Column::make('name')->title(__('asset name'))->width(600),
            Column::make('taxable.tax_label.code')->title(__('Tax label')),
            Column::make('taxable.name')->title(__('taxable')),
            Column::make('tariff')->title(__('tarif'))->width(600),
            Column::make('width')->title(__('width')),
            Column::make('length')->title(__('length')),
            Column::make('seize')->title(__("Valeur d’assiette"))->addClass('text-nowrap'),
           // Column::make('bill_status')->title(__('status')),
            Column::make('invoice_id')->title(__('Invoice ID')),
            Column::make('invoice.type')->title(__('type')),

            Column::make('created_at')->title(__('created at'))->addClass('text-nowrap')->width(150),

        ];
    }
    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Export-TaxpayerTaxables_' . date('YmdHis');
    }



}
