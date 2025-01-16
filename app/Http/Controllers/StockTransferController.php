<?php
namespace App\Http\Controllers;
use App\DataTables\CollectorsDataTable;
use App\DataTables\StockTransfersDataTable;
use App\Models\User;
use App\Traits\HandlesDateFilters;
use Illuminate\Http\Request;
class StockTransferController extends Controller
{
    use  HandlesDateFilters;
    public function index(Request $request,CollectorsDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        $collectors = User::select('users.id', 'users.name as user_name', 'roles.name as role_name')
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('roles.name', "=",'collecteur')
            ->get();
        return $dataTable->with(
            [
                'startDate' => $this->s_date,
                'endDate' => $this->e_date,
            ]
        )->render('pages/stock_transfers.list', ['collectors' => $collectors]);
    }
    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $userId, StockTransfersDataTable $dataTable)
    {
        $this->handleDateFilters($request);
        $validatedData = $request->validate([
            's_date' => 'date_format:Y-m-d',
            'e_date' => 'date_format:Y-m-d',
        ]);
        $user = User::find($userId);
        $dateFrom = $validatedData['s_date'] ?? null;
        $dateTo = $validatedData['e_date'] ?? null;
        return $dataTable->with([
            'id'=>$user->id,
            'startDate' => $this->s_date,
            'endDate' => $this->e_date,
        ])->with('dateFrom', $dateFrom)->render('pages/stock_transfers.show', ['user' => $user, 'dateFrom' => $dateFrom, 'dateTo' => $dateTo]);
    }
}
