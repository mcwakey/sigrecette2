<?php

namespace App\Http\Middleware;

use App\Models\UserLogs;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogsUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check()) {

            $data = [
                'user_id' => auth()->id(),
                'ip_address' => $request->getClientIp(),
                'request' => json_encode([
                    'path' => $request->url(),
                    'path_info' => $request->getPathInfo(),
                    'method' => $request->method(),
                ]),
                'response' => json_encode([
                    'status' => $response->status(),
                    'status_text' => $response->statusText()
                ]),
            ];

            if ($request->routeIs('taxpayers.show')) {
                try {
                    $data['taxpayer_id'] = $request->route('taxpayer')->id;
                    UserLogs::create($data);
                } catch (\Exception $e) {}
                
            } else if (!$request->routeIs('taxpayers.*') && !$response->status() != 404) {
                UserLogs::create($data);
            }
        }

        return $response;
    }
}
