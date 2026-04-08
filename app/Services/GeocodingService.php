<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeocodingService
{
    public function getCoordinates(string $address): ?array
    {
        $apiKey = config('services.google.geocoding_api_key');

        $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
            'address' => $address,
            'key' => $apiKey,
        ]);

        if ($response->failed()) {
            return null;
        }

        $json = $response->json();

        if (
            isset($json['results'][0]['geometry']['location']) &&
            $json['status'] === 'OK'
        ) {
            return $json['results'][0]['geometry']['location']; // lat + lng
        }

        return null;
    }
}
