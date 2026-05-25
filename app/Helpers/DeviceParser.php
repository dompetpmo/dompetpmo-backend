<?php

namespace App\Helpers;

class DeviceParser
{
    public static function parse(string $userAgent): array
    {
        $ua = strtolower($userAgent);

        // Platform
        $platform = 'Unknown';
        if (str_contains($ua, 'android')) {
            $platform = 'Android';
        } elseif (str_contains($ua, 'iphone') || str_contains($ua, 'ipad')) {
            $platform = 'iOS';
        } elseif (str_contains($ua, 'windows')) {
            $platform = 'Windows';
        } elseif (str_contains($ua, 'macintosh') || str_contains($ua, 'mac os')) {
            $platform = 'macOS';
        } elseif (str_contains($ua, 'linux')) {
            $platform = 'Linux';
        }

        // Device type
        $deviceType = 'desktop';
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            $deviceType = 'mobile';
        } elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            $deviceType = 'tablet';
        } elseif (str_contains($ua, 'dart') || str_contains($ua, 'flutter') || str_contains($ua, 'okhttp')) {
            $deviceType = 'mobile';
            $platform   = str_contains($ua, 'android') ? 'Android' : 'Flutter';
        }

        // Browser / app name
        $browser = 'Unknown';
        if (str_contains($ua, 'dart') || str_contains($ua, 'flutter') || str_contains($ua, 'okhttp')) {
            $browser = 'Confindant Mobile';
        } elseif (str_contains($ua, 'edg/')) {
            $browser = 'Microsoft Edge';
        } elseif (str_contains($ua, 'chrome') && !str_contains($ua, 'chromium')) {
            $browser = 'Chrome';
        } elseif (str_contains($ua, 'firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($ua, 'safari') && !str_contains($ua, 'chrome')) {
            $browser = 'Safari';
        } elseif (str_contains($ua, 'opera') || str_contains($ua, 'opr/')) {
            $browser = 'Opera';
        }

        $deviceName = $browser !== 'Unknown'
            ? "{$browser} on {$platform}"
            : $platform;

        return [
            'device_name' => $deviceName,
            'device_type' => $deviceType,
            'platform'    => $platform,
        ];
    }
}
