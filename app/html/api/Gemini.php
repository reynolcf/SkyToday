<?php
class Gemini {
    private $apiKey;
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function sendRequest($weatherA, $weatherB) {
        if (!$this->apiKey) return "AI Summary unavailable (Key not set).";

        $prompt = "Compare these two weather reports and give a 2-sentence summary of the forecast: " . 
                  json_encode($weatherA) . " and " . json_encode($weatherB);

        $data = [
            "contents" => [[
                "parts" => [["text" => $prompt]]
            ]]
        ];

        $ch = curl_init($this->apiUrl . "?key=" . $this->apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);

        // Dig into the Gemini response structure
        return $result['candidates'][0]['content']['parts'][0]['text'] ?? "Could not generate AI summary.";
    }
}