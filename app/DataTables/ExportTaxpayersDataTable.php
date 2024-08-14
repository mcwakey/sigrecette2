<?php

namespace App\DataTables;

use App\Enums\TaxpayerStateEnums;
use App\Helpers\Constants;
use App\Models\Taxpayer;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\WithExportQueue;

class ExportTaxpayersDataTable extends DataTable
{
    use WithExportQueue;
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('id', function (Taxpayer $taxpayer) {
                return $taxpayer->id;
            })
            ->editColumn('name', function (Taxpayer $taxpayerinfo) {
                return $taxpayerinfo->name;
            })
            ->editColumn('social_work', function (Taxpayer $taxpayerinfo) {
                return $taxpayerinfo->social_work;
            })
            ->editColumn('gender', function (Taxpayer $taxpayer) {
                return $taxpayer->gender;
            })
            ->editColumn('mobilephone', function (Taxpayer $taxpayer) {
                return $taxpayer->mobilephone;
            })
            ->editColumn('telephone', function (Taxpayer $taxpayer) {
                return $taxpayer->telephone;
            })
            ->editColumn('id_type', function (Taxpayer $taxpayer) {
                return $taxpayer->id_type;
            })
            ->editColumn('id_number', function (Taxpayer $taxpayer) {
                return $taxpayer->id_number;
            })
            ->editColumn('nif', function (Taxpayer $taxpayer) {
                return $taxpayer->nif;
            })
            ->editColumn('address', function (Taxpayer $taxpayer) {
                return $taxpayer->address;
            })
            ->editColumn('longitude', function (Taxpayer $taxpayer) {
                return $taxpayer->longitude;
            })
            ->editColumn('latitude', function (Taxpayer $taxpayer) {
                return $taxpayer->latitude;
            })
            ->editColumn('category.name', function (Taxpayer $taxpayer) {
                return $taxpayer->category->name;
            })
            ->editColumn('activity.name', function (Taxpayer $taxpayer) {
                return $taxpayer->activity->name;
            })
            ->editColumn('other_work', function (Taxpayer $taxpayer) {
                return $taxpayer->other_work;
            })
            ->editColumn('authorisation', function (Taxpayer $taxpayer) {
                return __( $taxpayer->authorisation);
            })
            ->editColumn('auth_reference', function (Taxpayer $taxpayer) {
                return $taxpayer->auth_reference;
            })
            ->editColumn('town.canton.name', function (Taxpayer $taxpayer) {
                    return $taxpayer->town->canton->name;
            })
            ->editColumn('town.name', function (Taxpayer $taxpayer) {
                    return $taxpayer->town->name;
            })
            ->editColumn('zone.name', function (Taxpayer $taxpayer) {
                    return $taxpayer->zone->name;
            })




            ->editColumn('created_at', function (Taxpayer $taxpayer) {
                return $taxpayer->created_at->format('d M Y');
            })
            ->editColumn('updated_at', function (Taxpayer $taxpayer) {
                return $taxpayer->updated_at->format('d M Y');
            })

            ->setRowId('id');
    }

    public function query(Taxpayer $model): QueryBuilder
    {
       $query= $model->with(['category', 'activity', 'town.canton', 'zone'])
                    ->join('towns', 'taxpayers.town_id', '=', 'towns.id')
                    ->with('town.canton')
                    ->join('cantons', 'towns.canton_id', '=', 'cantons.id')
                    ->with('zone')
                    ->join('zones', 'taxpayers.zone_id', '=', 'zones.id')
            ->where('taxpayers.type', '=',Constants::TITRE)->select('taxpayers.*')
           ->newQuery();

        if ($this->state) {
            $query->where('taxpayers.from_mobile_and_validate_state','=',TaxpayerStateEnums::PENDING);
        }else{
            $query->where('taxpayers.from_mobile_and_validate_state', TaxpayerStateEnums::APPROVED)
                ->orWhereNull('taxpayers.from_mobile_and_validate_state');
        }
        if ($this->disable!==null && $this->disable) {
            $query->onlyTrashed();
        }

        return $query;
    }


    public function html(): HtmlBuilder
    {

        return $this->builder()
            ->setTableId('export-taxpayers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->drawCallbackWithLivewire()
            ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('id')->title(__('id')),
            Column::make('name')->title(__('taxpayer')),
            Column::make('social_work')->title(__('taxpayer.social_work')),
            Column::make('gender')->title(__('gender')),
            Column::make('mobilephone')->title(__('mobilephone'))->name("taxpayers.mobilephone"),
            Column::make('telephone')->title(__('telephone'))->name("taxpayers.telephone"),
            Column::make('id_type')->title(__('id_type')),
            Column::make('id_number')->title(__('id_number')),
            Column::make('nif')->title(__('nif')),
            Column::make('address')->title(__('address')),
            Column::make('longitude')->title(__('longitude')),
            Column::make('latitude')->title(__('latitude')),
            Column::make('category.name')->title(__('taxpayer.category')),
            Column::make('activity.name')->title(__('activity')),
            Column::make('other_work')->title(__('other_work'))->name("other_work"),
            Column::make('authorisation')->title(__('authorisation'))->name("authorisation"),
            Column::make('auth_reference')->title(__('auth_reference'))->name("auth_reference"),
            Column::make('town.canton.name')->title(__('canton')),
            Column::make('town.name')->title(__('Villages/Quartiers')),
            Column::make('zone.name')->title(__('zone'))->name("zone.name"),

            Column::make('created_at')->title(__('created at')),
            Column::make('updated_at')->title(__('updated_at')),

        ];


        return $columns;
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Export-Taxpayers_' . date('YmdHis');
    }
}
