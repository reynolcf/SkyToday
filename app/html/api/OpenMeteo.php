<?php
// OpenMeteo.php

class OpenMeteo {
    private function curlGet(string $url): array {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

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

        $query = http_build_query([
        "latitude"          => $lat,
        "longitude"         => $lon,

        // ---- CURRENT CONDITIONS (data.current.* in JS) ----
        // (matches example from docs, minus uv_index) 
        "current"           => "temperature_2m,apparent_temperature,precipitation,relative_humidity_2m,pressure_msl,wind_speed_10m,wind_gusts_10m,weather_code,cloud_cover",

        // ---- HOURLY DATA (data.hourly.* in JS) ----
        // no `time` here, and no `uv_index`
        "hourly"            => "temperature_2m,apparent_temperature,precipitation,relative_humidity_2m,pressure_msl,wind_speed_10m,wind_gusts_10m,weather_code",

        // ---- DAILY AGGREGATES (data.daily.* in JS) ----
        // uv index only exists here as uv_index_max
        "daily"             => "temperature_2m_max,temperature_2m_min,precipitation_sum,uv_index_max,wind_speed_10m_max,wind_gusts_10m_max,snowfall_sum,precipitation_hours,weather_code",

        "forecast_days"     => 6,

        // Use imperial units to line up with *_f, *_mph, *_in in your JS
        "temperature_unit"  => "fahrenheit",
        "wind_speed_unit"   => "mph",
        "precipitation_unit"=> "inch",

        "timezone"          => "auto"
    ]);

        $url = "https://api.open-meteo.com/v1/forecast" . "?" . $query;
        $data = $this->curlGet($url);

        return $data;
    }
}

