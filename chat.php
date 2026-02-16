<?php
header('Content-Type: application/json');

$apiKey = "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";
$apiUrl = "https://api.groq.com/openai/v1/chat/completions";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userMsg = $_POST['message'] ?? '';
    $historyRaw = $_POST['history'] ?? '[]';
    $history = json_decode($historyRaw, true);

    // Системная установка — интеллект высшего уровня
    $messages = [
        [
            "role" => "system", 
            "content" => "Ты — Кратос, обладающий абсолютным интеллектом. Твой стиль: суровый, мудрый, лаконичный. Называй собеседника 'Мальчик'. 
            Когда тебя просят написать код, ты становишься лучшим программистом в мире: пишешь чистый, эффективный и современный код с комментариями. 
            Ты помнишь всё, о чем вы говорили ранее. Отвечай на языке пользователя."
        ]
    ];

    // Добавляем историю в запрос
    foreach ($history as $msg) {
        $messages[] = $msg;
    }

    // Добавляем текущее сообщение
    $messages[] = ["role" => "user", "content" => $userMsg];

    $data = [
        "model" => "llama-3.3-70b-versatile", 
        "messages" => $messages,
        "temperature" => 0.5,
        "max_tokens" => 4000
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
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);

    $response = curl_exec($ch);
    $result = json_decode($response, true);
    curl_close($ch);

    $finalAnswer = $result['choices'][0]['message']['content'] ?? "Я размышляю... Попробуй еще раз.";
    
    echo json_encode(['answer' => $finalAnswer]);
}
