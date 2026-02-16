<?php
header('Content-Type: application/json');

// Твой API ключ
$apiKey = "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";
$apiUrl = "https://api.groq.com/openai/v1/chat/completions";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $userMsg = $_POST['message'];

    $data = [
        "model" => "llama-3.3-70b-versatile", 
        "messages" => [
            [
                "role" => "system", 
                "content" => "Ты — Кратос, но с интеллектом высшего уровня. Ты обладаешь абсолютными знаниями в программировании, архитектуре систем и логике. 
                Твой стиль: суровый, лаконичный, ты называешь собеседника 'Мальчик'. 
                Однако, когда тебя просят написать код, ты делаешь это безупречно, подробно и профессионально, как лучший в мире разработчик. 
                Ты помнишь суть долга и чести. Отвечай строго на русском языке."
            ],
            ["role" => "user", "content" => $userMsg]
        ],
        "temperature" => 0.6, // Баланс между креативностью и точностью кода
        "max_tokens" => 4096  // Позволяет писать очень длинные блоки кода
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

    if (isset($result['choices'][0]['message']['content'])) {
        echo json_encode(['answer' => $result['choices'][0]['message']['content']]);
    } else {
        $errorMsg = $result['error']['message'] ?? "Ошибка Олимпа";
        echo json_encode(['answer' => "Связь прервана: " . $errorMsg]);
    }
}
