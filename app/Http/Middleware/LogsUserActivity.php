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
            UserLogs::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->getClientIp(),
                'request' => json_encode([
                    'path' => $request->url(),
                    'method' => $request->method(),
                ]),
                'response' => json_encode([
                    'status' => $response->status(),
                    //'content' => $response->getContent(),
                ]),
            ]);
        }

        return $response;
    }
}
