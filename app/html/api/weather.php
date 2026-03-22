<?php

header('Content-Type: application/json');
// this is to hide error output so it doesn’t break JSON
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once "WeatherAPI.php";
require_once "OpenMeteo.php";
require_once "Location.php";
require_once "Gemini.php"; 

$query = $_GET['query'] ?? null;

try {
    if ($query === null) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "error"   => "Missing zipcode"
        ]);
        exit;
    }

    $locationer = new Location();
    $geoResult = $locationer->getLocation($query);
    $lat = $geoResult["lat"];
    $lon = $geoResult["lon"];

    // call the apis
    $weatherApiKey = getenv('WEATHER_API_KEY');
    $geminiKey     = getenv('GEMINI_API_KEY');

    $weatherApi = new WeatherAPI($weatherApiKey);
    $openMeteo  = new OpenMeteo();
    $gemini     = new Gemini($geminiKey);
    
    // 1. ISOLATE WEATHER API A
    try {
        $weatherDataA = $weatherApi->getWeather($lat, $lon);
    } catch (Exception $e) {
        // If this hits a rate limit, we catch it here and provide a fallback array
        $weatherDataA = ["error" => "WeatherAPI limits reached or data unavailable."];
    }

    // 2. ISOLATE OPENMETEO
    try {
        $weatherDataB = $openMeteo->getWeather($lat, $lon);
    } catch (Exception $e) {
        // Same here, provide a fallback array
        $weatherDataB = ["error" => "OpenMeteo limits reached or data unavailable."];
    }

    // 3. SEPARATE GEMINI SUMMARIES
    // Get AI summary for API A (Only if we actually got weather data)
    if (isset($weatherDataA['error'])) {
        $aiResponseA = "No weather data available to summarize.";
    } else {
        try {
            $aiResponseA = $gemini->sendRequest($weatherDataA);
        } catch (Exception $e) {
            $aiResponseA = "AI is currently offline for this summary.";
        }
    }

    // Get AI summary for API B (Only if we actually got weather data)
    if (isset($weatherDataB['error'])) {
        $aiResponseB = "No weather data available to summarize.";
    } else {
        try {
            $aiResponseB = $gemini->sendRequest($weatherDataB);
        } catch (Exception $e) {
            $aiResponseB = "AI is currently offline for this summary.";
        }
    }
  
    // 4. OUTPUT THE JSON
    echo json_encode([
        "success"      => true,
        "query"        => $query,
        "locationData" => $geoResult,
        "weatherapi"   => $weatherDataA,
        "openmeteo"    => $weatherDataB,
        "gemini_a"     => $aiResponseA,
        "gemini_b"     => $aiResponseB
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error"   => $e->getMessage()
    ]);
}