<?php

namespace App\Http\Middleware;

use App\Jobs\LogUserActivity;
use Closure;
use Illuminate\Http\Request;

class LogsUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($this->shouldLog($request, $response)) {
            $request->attributes->set('log_user_activity_data', $this->buildData($request, $response));
        }
        return $response;
    }

    public function terminate(Request $request, $response): void
    {
        $data = $request->attributes->get('log_user_activity_data');
        if (!is_array($data)) {
            return;
        }

        LogUserActivity::dispatch($data);
    }

    private function shouldLog(Request $request, $response): bool
    {
        if (!auth()->check()) {
            return false;
        }

        if ($request->is('api/v1/user/notifications')) {
            return false;
        }

        $status = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null;
        if ($request->routeIs('taxpayers.*')) {
            return $request->routeIs('taxpayers.show');
        }

        return $status !== 404;
    }

    private function buildData(Request $request, $response): array
    {
        $data = [
            'user_id' => auth()->id(),
            'ip_address' => $request->getClientIp(),
            'request' => json_encode([
                'path' => $request->url(),
                'path_info' => $request->getPathInfo(),
                'method' => $request->method(),
            ]),
            'response' => json_encode([
                'status' => method_exists($response, 'getStatusCode') ? $response->getStatusCode() : null,
            ]),
        ];

        if ($request->routeIs('taxpayers.show')) {
            try {
                $data['taxpayer_id'] = $request->route('taxpayer')->id;
            } catch (\Exception $e) {
                // Ignore missing/invalid route model bindings.
            }
        }

        return $data;
    }
}
