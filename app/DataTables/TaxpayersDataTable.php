<?php

namespace App\DataTables;

use App\Enums\TaxpayerStateEnums;
use App\Helpers\Constants;
use App\Models\Taxpayer;
use App\Models\User;
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
            ->rawColumns(['name', 'last_login_at'])
            ->editColumn('taxpayers.id', function (Taxpayer $taxpayer) {
                return $taxpayer->id;
            })
            ->editColumn('taxpayer.name', function (Taxpayer $taxpayerinfo) {
                return view('pages/taxpayers.columns._taxpayer', ['taxpayerinfo' => $taxpayerinfo]);
            })
            ->editColumn('gender', function (Taxpayer $taxpayer) {
                return $taxpayer->gender;
            })
            ->editColumn('mobilephone', function (Taxpayer $taxpayerinfo) {
                return view('pages/taxpayers.columns._phone', ['taxpayerinfo' => $taxpayerinfo]);
            })
            ->editColumn('town.canton.name', function (Taxpayer $taxpayer) {
                return $taxpayer->town->canton->name;
            })
            ->editColumn('town.name', function (Taxpayer $taxpayer) {
                return $taxpayer->town->name;
            })
            ->editColumn('address', function (Taxpayer $taxpayer) {
                return $taxpayer->address;
            })
            ->editColumn('zone.name', function (Taxpayer $taxpayer) {
                return $taxpayer->zone->name;
            })
            ->editColumn('status', function (Taxpayer $taxpayerinfo) {
                return view('pages/taxpayers.columns._aproval', ['taxpayerinfo' => $taxpayerinfo]);
            })
            ->editColumn('created_at', function (Taxpayer $taxpayer) {
                return "Créé le: " . $taxpayer->created_at->format('d M Y');
            })
            ->editColumn('created_by', function (Taxpayer $taxpayer) {
                $createdByName = null;
                $updatedByName = null;
                $createdAt = null;
                $updatedAt = null;
                if ($taxpayer->created_by) {
                    $createdByUser = User::find($taxpayer->created_by);
                    if ($createdByUser) {
                        $createdByName = $createdByUser->name;
                    }
                    $createdAt = $taxpayer->created_at ? $taxpayer->created_at->format('d/m/Y H:i:s') : null;
                }
                if ($taxpayer->updated_by) {
                    $updatedByUser = User::find($taxpayer->updated_by);
                    if ($updatedByUser) {
                        $updatedByName = $updatedByUser->name;
                    }
                    $updatedAt = $taxpayer->updated_at ? $taxpayer->updated_at->format('d/m/Y H:i:s') : null;
                }
                if ($createdByName && !$updatedByName) {
                    $name = "Créé par: " . $createdByName;
                    $date = "Le: " . $createdAt;
                } elseif ($updatedByName && $createdByName) {
                    $name = "Créé par: " . $createdByName . " | Dernière édition par: " . $updatedByName;
                    $date = "Créé le: " . $createdAt . " | Dernière édition le: " . $updatedAt;
                } elseif (!$createdByName && $updatedByName) {
                    $name = "Dernière édition par: " . $updatedByName;
                    $date = "Le: " . $updatedAt;
                } else {
                    $name = "Inconnu";
                    $date = "Inconnu";
                }
                return $name . " " . $date;
            })
            ->addColumn('action', function (Taxpayer $taxpayer) {
                return view('pages/taxpayers.columns._actions', ['taxpayer' => $taxpayer]);
            })
            ->setRowId('id');
    }
    /**
     * Get the query source of dataTable.e
     */
    public function query(Taxpayer $model): QueryBuilder
    {
        $query = $model->with('town')
            ->join('towns', 'taxpayers.town_id', '=', 'towns.id')
            ->with('town.canton')
            ->join('cantons', 'towns.canton_id', '=', 'cantons.id')
            ->with('zone')
            ->join('zones', 'taxpayers.zone_id', '=', 'zones.id')
            ->where('taxpayers.type', '=', Constants::TITRE)->select('taxpayers.*')
            ->newQuery();
        if ($this->disable !== null && $this->disable) {
            $query = $query->onlyTrashed();
        } elseif ($this->state) {
            $query = $query->where('taxpayers.from_mobile_and_validate_state', '=', TaxpayerStateEnums::PENDING);
        } else {
            $query = $query->where(function ($q) {
                $q->whereNotIn('taxpayers.from_mobile_and_validate_state', [
                    TaxpayerStateEnums::REJECTED,
                    TaxpayerStateEnums::PENDING
                ])->orWhereNull('taxpayers.from_mobile_and_validate_state');
            });
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
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->drawCallbackWithLivewire()
            ->orderBy(0, 'desc')
            ->pageLength(50)
            ->lengthMenu([[50,100, 300, 500, -1], [50,100, 300, 500, "All"]])
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/taxpayers/columns/_draw-scripts.js')) . "}");
    }
    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        $columns = [
            Column::make('taxpayers.id')->title(__('id'))->visible(false),
            Column::make('taxpayer.name')->title(__('taxpayer'))->addClass('d-flex align-items-center text-uppercase ')->name("taxpayers.name"),
            Column::make('gender')->title(__('gender')),
            Column::make('mobilephone')->title(__('mobilephone'))->name("taxpayers.mobilephone"),
            Column::make('town.canton.name')->title(__('canton')),
            Column::make('town.name')->title(__('Villages/Quartiers')),
            Column::make('address')->title(__('address')),
            Column::make('zone.name')->title(__('zone'))->name("zone.name"),
            Column::make('status')->title(__('aproval'))->searchable(false),
            Column::make('created_at')->title(__('created at'))->addClass('text-nowrap created_at'),
            Column::make('created_by')->title(__('user'))->addClass('d-flex align-items-center'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(60)
        ];
        return array_map(function ($column) {
            if (request()->has('rc') && in_array($column->name, ['action', 'status'])) {
                $column->visible(false);
            }
            if (!request()->has('state') &&  in_array($column->name, ['created_by','status','town.canton.name'])) {
                $column->visible(false);
            }
            return $column;
        }, $columns);
    }
    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Taxpayers_' . date('YmdHis');
    }
}
