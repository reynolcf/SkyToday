<?php
// OpenMeteo.php

class OpenMeteo {
    private function curlGet(string $url): array {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // --- ADDED THESE TWO LINES FOR DOCKER COMPATIBILITY ---
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // ------------------------------------------------------

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception("OpenMeteo CURL error: " . curl_error($ch));
        }

        curl_close($ch);

        $json = json_decode($response, true);
        if (!$json) {
            throw new Exception("OpenMeteo returned invalid JSON.");
        }

        return $json;
    }

    public function getWeather(float $lat, float $lon): array {
        // We use a simple array and let http_build_query handle the encoding
        $params = [
            "latitude"  => $lat,
            "longitude" => $lon,
            "current"   => "temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,pressure_msl,wind_speed_10m,wind_gusts_10m",
            "hourly"    => "temperature_2m,weather_code",
            "daily"     => "weather_code,temperature_2m_max,temperature_2m_min,precipitation_sum,uv_index_max,wind_speed_10m_max,snowfall_sum",
            "temperature_unit"   => "fahrenheit",
            "wind_speed_unit"    => "mph",
            "precipitation_unit" => "inch",
            "timezone"           => "auto"
        ];

        $url = "https://api.open-meteo.com/v1/forecast?" . http_build_query($params);
        
        $data = $this->curlGet($url);

        // ADDED: Check if OpenMeteo returned an error payload (like a rate limit)
        if (isset($data['error']) && $data['error'] === true) {
            $reason = $data['reason'] ?? 'Unknown OpenMeteo error';
            throw new Exception("OpenMeteo API error: " . $reason);
        }

        return $data;
    }
}