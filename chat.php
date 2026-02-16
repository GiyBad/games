<?php
header('Content-Type: application/json');

// Твой рабочий ключ Groq
$apiKey = "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";
$apiUrl = "https://api.groq.com/openai/v1/chat/completions";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $userMsg = $_POST['message'];

    $data = [
        "model" => "llama3-8b-8192",
        "messages" => [
            ["role" => "system", "content" => "Ты Кратос из God of War. Ответы суровые, короткие. Называй юзера 'Мальчик'. Язык - русский."],
            ["role" => "user", "content" => $userMsg]
        ],
        "temperature" => 0.6
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json"
    ]);

    // Важно для стабильности на облачных хостингах
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    if (isset($result['choices'][0]['message']['content'])) {
        echo json_encode(['answer' => $result['choices'][0]['message']['content']]);
    } else {
        $errorMsg = $result['error']['message'] ?? "Ошибка API";
        echo json_encode(['answer' => "Связь с Олимпом прервана: $errorMsg"]);
    }
} else {
    echo json_encode(['answer' => "Говори, не молчи!"]);
}
