<?php
header('Content-Type: application/json');

// Твой рабочий ключ Groq
$apiKey = "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";
$apiUrl = "https://api.groq.com/openai/v1/chat/completions";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $userMsg = $_POST['message'];

    $data = [
        "model" => "llama-4-scout", // Используем актуальную модель из твоего списка
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

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    if (isset($result['choices'][0]['message']['content'])) {
        echo json_encode(['answer' => $result['choices'][0]['message']['content']]);
    } else {
        // Вывод ошибки для отладки, если что-то не так
        $errorMsg = $result['error']['message'] ?? "Неизвестная ошибка Олимпа";
        echo json_encode(['answer' => "Связь прервана: " . $errorMsg]);
    }
} else {
    echo json_encode(['answer' => "Говори, не молчи!"]);
}
