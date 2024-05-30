<?php

namespace App\DataTables;

use App\Helpers\Constants;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class UsersDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['user', 'last_login_at'])
            ->editColumn('user', function (User $user) {
                return view('pages/apps.user-management.users.columns._user', compact('user'));
            })
            ->editColumn('role', function (User $user) {
                return ucwords(__($user->roles->first()?->name));
            })
            ->editColumn('last_login_at', function (User $user) {
                return sprintf('<div class="badge badge-light fw-bold">%s</div>', $user->last_login_at ? $user->last_login_at->diffForHumans() : $user->updated_at->diffForHumans());
            })
            ->editColumn('created_at', function (User $user) {
                return \Carbon\Carbon::parse($user->created_at)->locale('fr')->isoFormat('LL');
            })
            ->addColumn('action', function (User $user) {
                return view('pages/apps.user-management.users.columns._actions', compact('user'));
            })
            ->setRowId('id');
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $query= $model->newQuery();

            $role = Role::where('name', 'administrateur_system')->first();
            $roleUsers = $role->users()->pluck('id')->toArray();
            $query->whereNotIn('users.id', $roleUsers);

        
        if($this->disable !== null && $this->disable){
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
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('rt' . "<'row'<'col-sm-12 col-md-5'l><'col-sm-12 col-md-7'p>>",)
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(2)
            ->drawCallback("function() {" . file_get_contents(resource_path('views/pages/apps/user-management/users/columns/_draw-scripts.js')) . "}");
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('user')->addClass('d-flex align-items-center')->name('name')->title(__('user')),
            Column::make('role')->searchable(false),
            Column::make('last_login_at')->title(__('last_login')),
            Column::make('created_at')->title(__('joined_date'))->addClass('text-nowrap'),
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
        return 'Users_' . date('YmdHis');
    }
}
