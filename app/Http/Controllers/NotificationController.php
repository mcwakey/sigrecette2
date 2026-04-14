<?php

namespace App\Http\Controllers;

use App\Models\Taxpayer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notifications()
    {
        $data = [];
        $userId = Auth::user()->id;
        $user = User::find($userId);
        $notifications = $user->unreadNotifications;
        foreach ($notifications as $value) {
            $taxpayer = isset($value->data['taxpayer_id']) ? Taxpayer::find($value->data['taxpayer_id']) : null;
            $date = Carbon::parse($value->created_at)->diffForHumans();
            $tempData = [
                'notification' => $value,
                'taxpayer' => $taxpayer,
                'date' => $date,
            ];
            $data[] = $tempData;
        }
        return response()->json([['data' => $data, 'size' => count($notifications)], 200]);
    }
    public function updateNotification(Request $request)
    {
        $notifId = $request->input('notif_id');
        $user = Auth::user();
        if ($notifId) {
            $user->notifications->where('id', "=", $notifId)->markAsRead();
        }
        $userId = $user->id;
        $user = User::find($userId);
        $notifications = $user->unreadNotifications;
        return response()->json(['notif_id' => $notifId, 'size' => count($notifications)], 200);
    }
    public function clear()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->notifications()->delete();
        return response()->json(['message' => 'Notifications deleted.'], 200);
    }
    public function markAsRead()
    {
        /** @var User $user */
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Notifications mark as read.'], 200);
    }
}
