<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GetPublicService
{
    public static function getServerIP()
    {
        try {
            $response = Http::timeout(5)->get('https://ifconfig.me/all.json');
            if ($response->successful()) {
                $data = $response->json();
                return $data['ip_addr'] ?? self::handleError('IP address not found in response.');
            }

            return self::handleError('Response not successful with status ' . $response->status());

        } catch (\Illuminate\Http\Client\RequestException $e) {
            return self::handleError('HTTP request error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return self::handleError('General error: ' . $e->getMessage());
        }
    }

    private static function handleError($message)
    {
        if (app()->environment('production')) {
            return 'null';
        }

        return 'Erreur: ' . $message;
    }
}
