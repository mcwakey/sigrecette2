<?php

namespace App\Http\Middleware;

use App\Models\UserLogs;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Queue;
use App\Jobs\LogUserActivity;

class LogsUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if (auth()->check() &&  !request()->is('api/v1/user/notifications')) {
            $data = [
                'user_id' => auth()->id(),
                'ip_address' => $request->getClientIp(),
                'request' => json_encode([
                    'path' => $request->url(),
                    'path_info' => $request->getPathInfo(),
                    'method' => $request->method(),
                ]),
                'response' => json_encode([
                    'status' =>  method_exists($response, 'status') ?$response->status():null,
                    'status_text' => method_exists($response, 'statusText') ?$response->statusText():null
                ]),
            ];

            if ($request->routeIs('taxpayers.show')) {
                try {
                    $data['taxpayer_id'] = $request->route('taxpayer')->id;
                    Queue::push(new LogUserActivity($data));
                } catch (\Exception $e) {
                    // Handle exception (optional)
                }
            } else if (!$request->routeIs('taxpayers.*')&& method_exists($response, 'status') && $response->status() != 404) {
                Queue::push(new LogUserActivity($data));
            }
        }

        return $response;
    }
}
