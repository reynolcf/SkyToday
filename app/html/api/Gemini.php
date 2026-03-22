<?php
class Gemini {
    private $apiKey;
    // Updated to the stable endpoint
    private $apiUrl = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent";
    
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    // Changed to accept a single weather array
    public function sendRequest($weatherData) {
        if (!$this->apiKey) return "AI Summary unavailable (Key not set).";

        // Updated prompt to focus on a single dataset
        $prompt = "Provide a short, conversational 2-sentence summary of this weather report: " . json_encode($weatherData);

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

        // --- WINDOWS SSL FIX START ---
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // --- WINDOWS SSL FIX END ---

        $response = curl_exec($ch);
        
        if ($response === false) {
            $error = curl_error($ch);
            return "CURL Error: " . $error;
        }

        $result = json_decode($response, true);
        curl_close($ch);

        if (isset($result['error'])) {
            return "API Error: " . ($result['error']['message'] ?? 'Unknown error');
        }

        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        return "AI Summary unavailable (Empty response from Google).";
    }
}