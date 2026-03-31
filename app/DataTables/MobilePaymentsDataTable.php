<?php

namespace App\DataTables;

use App\Models\MobilePaymentTransaction;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

class MobilePaymentsDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query, Request $request): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->editColumn('reference', function (MobilePaymentTransaction $tx) {
                return '<span class="text-gray-800 fw-bold">' . e(substr($tx->reference, 0, 8)) . '...</span>';
            })
            ->editColumn('invoice_id', function (MobilePaymentTransaction $tx) {
                return $tx->invoice?->invoice_no ?? '-';
            })
            ->editColumn('taxpayer_id', function (MobilePaymentTransaction $tx) {
                return $tx->taxpayer?->name ?? '-';
            })
            ->editColumn('amount', function (MobilePaymentTransaction $tx) {
                return format_amount($tx->amount);
            })
            ->editColumn('phone_number', function (MobilePaymentTransaction $tx) {
                return $tx->phone_number;
            })
            ->editColumn('provider', function (MobilePaymentTransaction $tx) {
                return strtoupper($tx->provider);
            })
            ->editColumn('status', function (MobilePaymentTransaction $tx) {
                return view('pages.mobile-payments.columns._status', ['transaction' => $tx]);
            })
            ->editColumn('user_id', function (MobilePaymentTransaction $tx) {
                return $tx->user?->name ?? '-';
            })
            ->editColumn('created_at', function (MobilePaymentTransaction $tx) {
                return $tx->created_at?->format('d/m/Y H:i');
            })
            ->addColumn('action', function (MobilePaymentTransaction $tx) {
                return view('pages.mobile-payments.columns._actions', ['transaction' => $tx]);
            })
            ->rawColumns(['reference', 'status', 'action'])
            ->setRowId('id');
    }

    public function query(MobilePaymentTransaction $model): QueryBuilder
    {
        $query = $model->with(['invoice', 'taxpayer', 'user'])
            ->orderBy('created_at', 'desc');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('mobile-payments-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('mobile-payments.index', request()->all()))
            ->dom('rt<\'row\'<\'col-sm-12 col-md-5\'l><\'col-sm-12 col-md-7\'p>>')
            ->addTableClass('table align-middle table-row-dashed fs-6 gy-5 dataTable no-footer text-gray-600 fw-semibold')
            ->setTableHeadClass('text-start text-muted fw-bold fs-7 text-uppercase gs-0')
            ->orderBy(8)
            ->pageLength(100)
            ->lengthMenu([[100, 300, 500, -1], [100, 300, 500, "All"]]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('reference')->title('Référence'),
            Column::make('invoice_id')->title('N° Avis'),
            Column::make('taxpayer_id')->title('Contribuable'),
            Column::make('amount')->title('Montant'),
            Column::make('phone_number')->title('Téléphone'),
            Column::make('provider')->title('Fournisseur'),
            Column::make('status')->title('Statut'),
            Column::make('user_id')->title('Utilisateur'),
            Column::make('created_at')->title('Date'),
            Column::computed('action')
                ->addClass('text-end text-nowrap')
                ->exportable(false)
                ->printable(false)
                ->width(80),
        ];
    }

    protected function filename(): string
    {
        return 'MobilePayments_' . date('YmdHis');
    }
}
