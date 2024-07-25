<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GetPublicService
{
    public static function getServerIP()
    {
        try {
            $response = Http::timeout(10)->get('https://ifconfig.me/all.json');
            if ($response->successful()) {
                $serverIP = json_encode($response->body())["ip_addr"]  ;
            } else {
                $serverIP = 'Erreur: Réponse non réussie avec le statut ' . $response->status();
                if (app()->environment('production')) {
                    $serverIP  = 'null';
                }
            }
        } catch (\Exception $e) {
            $serverIP = 'Erreur lors de la récupération de l\'adresse IP: ' . $e->getMessage();
            if (app()->environment('production')) {
                $serverIP  = 'null';
            }
        }
        return $serverIP;
    }
}
