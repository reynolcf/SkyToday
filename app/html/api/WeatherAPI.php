<?php
// api/WeatherAPI.php

class WeatherAPI {
    private string $apiKey;

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

    private function curlGet(string $url): array {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        if ($response === false) {
            throw new Exception("WeatherAPI CURL error: " . curl_error($ch));
        }

        curl_close($ch);

        $json = json_decode($response, true);
        if (!is_array($json)) {
            throw new Exception("WeatherAPI returned invalid JSON.");
        }

        return $json;
    }

    public function getWeather(float $lat, float $lon): array {
        $query = http_build_query([
            'key'    => $this->apiKey,
            'q'      => "{$lat},{$lon}",
            'days'   => 6,
            'aqi'    => 'no',
            'alerts' => 'no',
        ]);

        $url  = "https://api.weatherapi.com/v1/forecast.json" . '?' . $query;
        $data = $this->curlGet($url);

        // if the weather api gave us an error (example would be a bad bad key)
        if (isset($data['error'])) {
            $message = $data['error']['message'] ?? 'Unknown WeatherAPI error';
            throw new Exception("WeatherAPI error: " . $message);
        }
        return $data;
    }
}