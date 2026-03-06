<?php

header('Content-Type: application/json');
// this is to hide error output so it doesn’t break JSON
error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

require_once "WeatherAPI.php";
require_once "OpenMeteo.php";
require_once "Location.php";
// require_once "OpenAI.php";
require_once "Gemini.php"; 

$query = $_GET['query'];

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
    $openAiKey     = getenv('OPENAI_API_KEY');
    $geminiKey = getenv('GEMINI_API_KEY');

    $weatherApi = new WeatherAPI($weatherApiKey);
    $openMeteo  = new OpenMeteo();
    // $openAI = new OpenAI($openAiKey);
    $gemini = new Gemini($geminiKey);
    

// ... inside your try block



// Call Gemini instead of OpenAI
    

    $weatherDataA = $weatherApi->getWeather($lat, $lon);
    $weatherDataB = $openMeteo->getWeather($lat, $lon);
    // $openAIResponse = $openAI->sendRequest($weatherDataA, $weatherDataB);

    try {
        $aiResponse = $gemini->sendRequest($weatherDataA, $weatherDataB);
    } catch (Exception $e) {
        $aiResponse = "AI is currently offline, but the data above is live!";
    }
  
    echo json_encode([
        "success"     => true,
        "query"       => $query,
        "locationData" => $geoResult,
        "weatherapi"  => $weatherDataA,
        "openmeteo"   => $weatherDataB,
        "openai" => $aiResponse
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "error"   => $e->getMessage()
    ]);
}