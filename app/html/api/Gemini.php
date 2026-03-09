<?php
class Gemini {
    private $apiKey;
    // Updated to the stable endpoint
    private $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent";

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function sendRequest($weatherA, $weatherB) {
        if (!$this->apiKey) return "AI Summary unavailable (Key not set).";

        $prompt = "Compare these two weather reports and give a short 2-sentence summary: " . 
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

        // --- WINDOWS SSL FIX START ---
        // These lines tell CURL to skip certificate verification (common on local Windows setups)
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        // --- WINDOWS SSL FIX END ---

        $response = curl_exec($ch);
        
        // Check if CURL itself failed (e.g., timeout or connection issue)
        if ($response === false) {
            $error = curl_error($ch);
            return "CURL Error: " . $error;
        }

        $result = json_decode($response, true);
        curl_close($ch);

        // Check if Gemini returned an API error (e.g., invalid key)
        if (isset($result['error'])) {
            return "API Error: " . ($result['error']['message'] ?? 'Unknown error');
        }

        // Return the text, or a specific error message if it's missing
        if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
            return $result['candidates'][0]['content']['parts'][0]['text'];
        }

        return "AI Summary unavailable (Empty response from Google).";
    }
}