<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Models\UserLogs;
use App\Models\Year;
use App\Traits\HandlesDateFilters;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserActivityController extends Controller
{
    use  HandlesDateFilters;
    public function index(Request $request)
    {

        $this->handleDateFilters($request);
        $validatedData = $request->validate([
            'user_id' => ['nullable', 'integer'],
        ]);
        $user_id = $validatedData['user_id'] ?? null;
        $logs_tab = UserLogs::with('user')
            ->when(
                $user_id, function ($query) use ( $user_id) {
                $query->where('user_id', $user_id);
            })
            ->whereBetween('created_at', [ $this->s_date,$this->e_date])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $s_date = $this->s_date;
        $e_date = $this->e_date;

       // dd($user_id);
        $logs = Cache::remember('user_activity_' . $s_date . '_' . $e_date, 60, function () use ( $user_id, $s_date, $e_date) {
            return UserLogs::selectRaw('
             DATE(user_logs.created_at) as date,
            COUNT(*) as total_requests,
            user_id,
            MAX(users.name) as user_name
        ')
                ->leftJoin('users', 'users.id', '=', 'user_logs.user_id')
                ->whereBetween('user_logs.created_at', [$s_date, $e_date])
                ->when( $user_id, function ($query) use ( $user_id) {
                    $query->where('user_logs.user_id',"=", $user_id);
                })
                ->groupBy('date', 'user_id')
                ->orderBy('date', 'asc')
                ->get();
        });
        //dd($logs[0]);
        $groupedLogs = $logs->groupBy('user_id');

        return view('pages/logs.index', compact('groupedLogs', 'logs','logs_tab'));
    }
}
