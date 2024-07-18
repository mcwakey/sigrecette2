<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Taxpayer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function notifications()
    {
        $data = [];
        $userId = Auth::user()->id;
        $user = User::find($userId);
        $notifications = $user->unreadNotifications;

        foreach ($notifications as $key => $value) {
            $taxpayer = Taxpayer::find($value->data['taxpayer_id']);
            $date = Carbon::parse($value->created_at)->diffForHumans();

            $tempData = [
                'notification' => $value,
                'taxpayer' => $taxpayer,
                'date' => $date,
            ];

            array_push($data, $tempData);
        }

        return response()->json([['data' => $data, 'size' => count($notifications)], 200]);
    }

    public function updateNotification(Request $request)
    {
        $notifId = $request->input('notif_id');

        $user = Auth::user();
        if ($notifId) {
            $user->notifications->where('id', $notifId)->markAsRead();
        }

        $userId = $user->id;
        $user = User::find($userId);
        $notifications = $user->unreadNotifications;

        return response()->json(['notif_id' => $notifId,'size' => count($notifications)], 200);
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
