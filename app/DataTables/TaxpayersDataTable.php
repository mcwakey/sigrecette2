<?php

namespace App\DataTables;

use App\Helpers\Constants;
use App\Models\Taxpayer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\WithExportQueue;

class TaxpayersDataTable extends DataTable
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
            // ->filter(function ($query) {
            //     if (request()->filled('search.value')) {
            //         $query->where('taxpayers.name', 'like', '%' . request('search.value') . '%')
            //         ->orWhere('taxables.name', 'like', '%' . request('search.value') . '%')
            //         ->orWhere('tax_labels.code', 'like', '%' . request('search.value') . '%');
            //         // Add additional search conditions as needed for other columns
            //     }
            // })
            ->rawColumns(['name', 'last_login_at'])
            ->editColumn('taxpayers.id', function (Taxpayer $taxpayer) {
                return $taxpayer->id;
            })
            ->editColumn('taxpayer.name', function (Taxpayer $taxpayerinfo) {
                return view('pages/taxpayers.columns._taxpayer', compact('taxpayerinfo'));
                //return $taxpayerinfo->name;
            })
            ->editColumn('gender', function (Taxpayer $taxpayer) {
                return $taxpayer->gender;
            })
            ->editColumn('mobilephone', function (Taxpayer $taxpayerinfo) {
                return view('pages/taxpayers.columns._phone', compact('taxpayerinfo'));
            })
            ->editColumn('town.canton.name', function (Taxpayer $taxpayer) {
                // if ($taxpayer->town) {
                    return $taxpayer->town->canton->name;
                // } else {
                //     return '';
                // }
                //return $taxpayer->town->canton->name;
            })
            ->editColumn('town.name', function (Taxpayer $taxpayer) {
                // if ($taxpayer->town){
                    return $taxpayer->town->name;
                // } else {
                //     return '';
                // }
            })

            ->editColumn('address', function (Taxpayer $taxpayer) {
                return $taxpayer->address;
            })
            ->editColumn('zone.name', function (Taxpayer $taxpayer) {
                // if ($taxpayer->zone){
                    return $taxpayer->zone->name;
                // } else {
                //     return '';
                // }
                //return $taxpayer->zone->name;
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
    // public function query(Taxpayer $model): QueryBuilder
    // {
    //     //return $model->newQuery();
    //     return $model->newQuery()
    //     ->with('town.canton', 'town', 'erea','zone');
    // }

    public function query(Taxpayer $model): QueryBuilder
    {
       $query= $model->with('town')
                    ->join('towns', 'taxpayers.town_id', '=', 'towns.id')
                    ->with('town.canton')
                    ->join('cantons', 'towns.canton_id', '=', 'cantons.id')
                   // ->with('erea')
                  //  ->join('ereas', 'taxpayers.erea_id', '=', 'ereas.id')
                    ->with('zone')
                    ->join('zones', 'taxpayers.zone_id', '=', 'zones.id')
            ->where('taxpayers.type', '=',Constants::TITRE)->select('taxpayers.*') // Select columns from taxpayers table
           ->newQuery();

        if ($this->disable!==null && $this->disable) {
            $query->onlyTrashed();
        }

        return $query;
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
            // ->dom("") // Add pagination ('p') and other controls ('i') at the bottom
            // ->dom("<'d-flex justify-content-end'B> ".'rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(0)
            ->drawCallbackWithLivewire()
            // ->buttons(['print','excel','csv','pdf',])
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayers/columns/_draw-scripts.js')) . "}")
            ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('taxpayers.id')->title(__('id'))->visible(false),
            Column::make('taxpayer.name')->title(__('taxpayer'))->addClass('d-flex align-items-center text-uppercase ')->name("taxpayers.name"),
            Column::make('gender')->title(__('gender')),
            Column::make('mobilephone')->title(__('mobilephone'))->name("taxpayers.mobilephone"),
            Column::make('town.canton.name')->title(__('canton')),
            Column::make('town.name')->title(__('Villages/Quartiers')),
            //Column::make('erea.name')->title(__('erea')),
            Column::make('address')->title(__('address')),
            Column::make('zone.name')->title(__('zone'))->name("zone.name"),
            //Column::make('created_at')->title(__('created at'))->addClass('text-nowrap created_at')->visible(false),
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
