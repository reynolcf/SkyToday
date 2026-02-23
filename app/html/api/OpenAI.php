<?php
// api/WeatherAPI.php

class OpenAI {
    private string $apiKey;

    public function __construct(string $apiKey) {
        $this->apiKey = $apiKey;
    }

     public function sendRequest($w1, $w2) {

        $prompt = "BE AS FAST AS POSSIBLE! I am going to give you two JSON strings of api responses from 2 different weather apis.
        I want you to sum up the weather and give me 2 separate responses for each API. Put it some format like this:
         \"We’ll start the day with some clouds, followed by clearer skies in the afternoon. Temperatures stay moderate with only a small chance of light rain.\"
                Return the output in JSON with this exact structure: 
                {
                    \"summary1\": \"...\",
                    \"summary2\": \"...\"
                }. Here are the two API data. API 1: " . json_encode($w1, JSON_PRETTY_PRINT) . " API 2: ". json_encode($w2, JSON_PRETTY_PRINT);

        $payload = [
            "model" => "gpt-5",
            "messages" => [
                ["role" => "user", "content" => $prompt]
            ]
        ];

        $ch = curl_init("https://api.openai.com/v1/chat/completions");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->apiKey}"
            ],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if (isset($data["error"])) {
            $messsage = $data["error"]["message"] ?? "OpenAI error";
            http_response_code(400);
            echo json_encode([
                "success" => false,
                "error"   => "OpenAI error: " . $message
            ]);
            exit;
        }
   
        $content = $data["choices"][0]["message"]["content"] ?? null;

        if (!$content) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "error"   => "Invalid OpenAI response",
            ]);
            exit;
        }

        // make it a php array
        $decoded = json_decode($content, true);

        if (!is_array($decoded)) {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "error"   => "OpenAI did not decode properly",
                "raw"     => $content
            ]);
            exit;
        }

        return $decoded;   // <-- RETURN AN ARRAY
    }
}