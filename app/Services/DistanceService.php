<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DistanceService
{
    /**
     * Haversine formula via SQL to find drivers within radius (km)
     * $lat, $lng in decimal degrees
     */
    public function driversWithinRadius(float $lat, float $lng, float $radiusKm = 10, int $limit = 10)
    {
        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(drivers.lat)) * cos(radians(drivers.lng) - radians($lng)) + sin(radians($lat)) * sin(radians(drivers.lat))))";

        $drivers = DB::table('drivers')
            ->select('drivers.*', DB::raw("{$haversine} as distance_km"))
            ->whereNotNull('drivers.lat')
            ->whereNotNull('drivers.lng')
            ->where('drivers.is_online', true)
            ->having('distance_km', '<=', $radiusKm)
            ->orderBy('distance_km')
            ->limit($limit)
            ->get();

        return $drivers;
    }

    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return round($earthRadius * $c, 2);
    }
}
