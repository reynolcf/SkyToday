<?php

class Location
{
    public function getLocation(string $input)
    {
        $input = trim($input);
        if ($input === '') {
            throw new Exception("No location input");
        }
        $result = $this->openMeteo($input);
        if ($result !== null) {
            return $result;
        }
        throw new Exception("Did not find location: '{$input}'.");
    }

    private function openMeteo(string $query)
    {
        $params = [
            'name'     => $query,
            'count'    => 1,
            'language' => 'en',
            'format'   => 'json'
        ];

        $url = "https://geocoding-api.open-meteo.com/v1/search" . '?' . http_build_query($params);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
      

        $data = json_decode($response, true);
        if (!is_array($data) || empty($data['results'])) {
            return null;
        }

        $r = $data['results'][0];

        return [
            'lat'  => $r['latitude'] ?? null,
            'lon'  => $r['longitude'] ?? null,
            'name' => $r['name'] ?? null,
            'elevation' => $r['elevation'] ?? null,
            'country' => $r['country'] ?? null,
            'state' => $r['admin1'] ?? null,
            'population' => $r['population'] ?? null

        ];
    }

}