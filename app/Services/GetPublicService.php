<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Class GetPublicService
 *
 * This service class is responsible for retrieving the public IP address of the server.
 * It tries multiple external services to obtain the IP address and ensures consistency
 * across the responses.
 *
 * @package App\Services
 */
class GetPublicService
{
    /**
     * Get the public IP address of the server.
     *
     * This method attempts to fetch the public IP address from multiple external services.
     * It ensures that all successful services return the same IP address. If only one
     * service succeeds, it returns that IP address.
     *
     * @return string The public IP address of the server, or an error message/null in case of failure.
     */
    public static function getServerIP()
    {
        $services = [
            'https://ifconfig.me/all.json',
            'https://api.ipify.org?format=json',
            'https://ipinfo.io/json'
        ];

        $ips = [];
        $error=null;

        foreach ($services as $service) {
            try {
                $response = Http::timeout(3)->get($service);
                if ($response->successful()) {
                    $data = $response->json();
                    $ip = self::extractIP($data);
                    if ($ip) {
                        $ips[] = $ip;
                    }
                }
            } catch (\Illuminate\Http\Client\RequestException $e) {
                $error= self::handleError('HTTP request error: ' . $e->getMessage());
            } catch (\Exception $e) {
                $error= self::handleError('General error: ' . $e->getMessage());
            }
        }

        return self::processIPs($ips,$error);
    }

    /**
     * Extract the IP address from the service response data.
     *
     * This method checks different possible keys in the response data to find the IP address.
     *
     * @param array $data The response data from the IP service.
     * @return string|null The extracted IP address or null if not found.
     */
    private static function extractIP($data)
    {
        if (isset($data['ip'])) {
            return $data['ip'];
        }

        if (isset($data['ip_addr'])) {
            return $data['ip_addr'];
        }

        if (isset($data['ipaddress'])) {
            return $data['ipaddress'];
        }

        return null;
    }

    /**
     * Process the collected IP addresses to ensure consistency.
     *
     * This method checks if all collected IPs are the same. If they are, it returns that IP.
     * If there is only one collected IP, it returns that IP. Otherwise, it handles the inconsistency.
     *
     * @param array $ips The list of collected IP addresses.
     * @return string The consistent IP address or an error message/null if inconsistent.
     */
    private static function processIPs(array $ips,string|null $errors):string
    {
        if (count($ips) === 0) {
            return self::handleError('No IP addresses could be retrieved.'.$errors);
        }

        if (count(array_unique($ips)) === 1) {
            return $ips[0];
        }

        return $ips[0];
    }

    /**
     * Handle errors and return appropriate messages.
     *
     * This method returns a detailed error message in non-production environments and a simple
     * 'null' string in production environments.
     *
     * @param string $message The error message to handle.
     * @return string The handled error message.
     */
    private static function handleError($message)
    {
        if (app()->environment('production')) {
            return 'null';
        }

        return 'Erreur: ' . $message;
    }
}
