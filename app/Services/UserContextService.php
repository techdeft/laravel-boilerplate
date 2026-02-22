<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UserContextService
{
    /**
     * Get the client's IP address (supports IPv4 and IPv6).
     */
    public function getIp(Request $request): ?string
    {
        return $request->ip();
    }

    /**
     * Get the user's device based on the User-Agent string.
     */
    public function getDevice(Request $request): string
    {
        $agent = $request->userAgent() ?? '';

        if (empty($agent)) {
            return 'Unknown Device';
        }

        if (preg_match('/windows/i', $agent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $agent)) {
            return 'Mac';
        } elseif (preg_match('/linux/i', $agent)) {
            if (preg_match('/android/i', $agent)) {
                return preg_match('/mobile/i', $agent) ? 'Android Mobile' : 'Android Tablet';
            }
            return 'Linux';
        } elseif (preg_match('/iphone/i', $agent)) {
            return 'iPhone';
        } elseif (preg_match('/ipad/i', $agent)) {
            return 'iPad';
        }

        return 'Other Device';
    }

    /**
     * Extract the Country ISO2 code from the IP address.
     * Uses a free API (ipinfo.io), cached for 14 days per IP to prevent rate limits.
     */
    public function getCountryIso2(?string $ip): ?string
    {
        if (empty($ip) || in_array($ip, ['127.0.0.1', '::1', '0.0.0.0'])) {
            return null; // Localhost or invalid IP
        }

        return Cache::remember("country_iso2_for_{$ip}", now()->addDays(14), function () use ($ip) {
            try {
                // ipinfo.io endpoint supports both IPv4 and IPv6 out of the box.
                $response = Http::timeout(5)->get("https://ipinfo.io/{$ip}/json");

                if ($response->successful()) {
                    return $response->json('country'); // returns ISO2 e.g., "US", "CA", "GB"
                }
            } catch (\Exception $e) {
                // Silently ignore exception, fallback to null
            }

            return null;
        });
    }

    /**
     * Helper to get login context all at once.
     * 
     * @return array{ip_address: string|null, device: string, country: string|null}
     */
    public function getLoginContext(Request $request): array
    {
        $ip = $this->getIp($request);

        return [
            'ip_address' => $ip,
            'device' => $this->getDevice($request),
            'country' => $this->getCountryIso2($ip),
        ];
    }
}
