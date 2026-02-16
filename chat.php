<?php
header('Content-Type: application/json');

// Твой ключ, который ты скинул
$apiKey = "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";
$apiUrl = "https://api.groq.com/openai/v1/chat/completions";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $userMsg = $_POST['message'];

    $data = [
        "model" => "llama3-8b-8192",
        "messages" => [
            ["role" => "system", "content" => "Ты Кратос. Твои ответы суровы, кратки и на русском. Называй юзера 'Мальчик'."],
            ["role" => "user", "content" => $userMsg]
        ],
        "temperature" => 0.5
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiKey",
        "Content-Type: application/json",
        "User-Agent: KratosGPT_App" // Некоторые API требуют User-Agent
    ]);

    // Пробуем пробить ограничения бесплатного хостинга
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, _HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo json_encode(['answer' => "Ошибка cURL: $error. Скорее всего, хостинг блокирует внешние запросы."]);
    } elseif ($httpCode !== 200) {
        echo json_encode(['answer' => "Боги разгневаны (Код: $httpCode). Проверь API ключ."]);
    } else {
        $result = json_decode($response, true);
        $text = $result['choices'][0]['message']['content'] ?? "Я промолчу...";
        echo json_encode(['answer' => $text]);
    }
} else {
    echo json_encode(['answer' => "Пустой запрос, мальчик."]);
}