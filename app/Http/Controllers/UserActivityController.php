<?php

namespace App\Http\Controllers;

use App\Helpers\Constants;
use App\Models\UserLogs;
use App\Models\Year;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class UserActivityController extends Controller
{
    public function index(Request $request)
    {
        $year = Year::getActiveYear()->name;

        $validatedData = $request->validate([
            's_date' => 'nullable|date_format:Y-m-d',
            'e_date' => 'nullable|date_format:Y-m-d',
            'user_id' => ['nullable', 'integer'],
        ]);
        $startDate = $validatedData['s_date'] ?? Carbon::parse("{$year}-01-01 00:00:00");
        $endDate = $validatedData['e_date'] ?? Carbon::parse("{$year}-12-31 23:59:59");
        $logs_tab = UserLogs::with('user')
            ->when($request->user_id, function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $logs = Cache::remember('user_activity_' . $startDate . '_' . $endDate, 60, function () use ($startDate, $endDate, $request) {
            return UserLogs::selectRaw('
                     DATE(user_logs.created_at) as date,
                    COUNT(*) as total_requests,
                    user_id,
                    MAX(users.name) as user_name
                ')
                ->leftJoin('users', 'users.id', '=', 'user_logs.user_id')
                ->whereBetween('user_logs.created_at', [$startDate, $endDate])
                ->when($request->user_id, function ($query) use ($request) {
                    $query->where('user_logs.user_id', $request->user_id);
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
