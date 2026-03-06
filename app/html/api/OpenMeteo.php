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
        $params = [
            "latitude"           => $lat,
            "longitude"          => $lon,
            // Added 'time' to current and hourly as your JS looks for it
            "current"            => "time,temperature_2m,apparent_temperature,precipitation,relative_humidity_2m,pressure_msl,wind_speed_10m,wind_gusts_10m,weather_code",
            "hourly"             => "time,temperature_2m,weather_code", 
            "daily"              => "time,temperature_2m_max,temperature_2m_min,precipitation_sum,uv_index_max,wind_speed_10m_max,snowfall_sum,weather_code",
            "forecast_days"      => 6,
            "temperature_unit"   => "fahrenheit",
            "wind_speed_unit"    => "mph",
            "precipitation_unit" => "inch",
            "timezone"           => "auto"
        ];

        $url = "https://api.open-meteo.com/v1/forecast?" . http_build_query($params);
        return $this->curlGet($url);
    }
}