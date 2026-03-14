<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureSyncIpAllowed
{
    public function handle(Request $request, Closure $next)
    {
        $allowed = config('sync.allowed_ips', []);
        if (is_string($allowed)) {
            $allowed = array_filter(array_map('trim', explode(',', $allowed)));
        }

        if (empty($allowed)) {
            return $next($request);
        }

        $ip = $request->ip();
        if (!in_array($ip, $allowed, true)) {
            Log::warning('sync ip blocked', ['ip' => $ip]);
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
