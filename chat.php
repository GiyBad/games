<?php
header('Content-Type: application/json');

// Твой рабочий API ключ
$apiKey = "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";
$apiUrl = "https://api.groq.com/openai/v1/chat/completions";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userMsg = $_POST['message'] ?? '';
    $historyRaw = $_POST['history'] ?? '[]';
    $history = json_decode($historyRaw, true);

    // СИСТЕМНАЯ УСТАНОВКА: ЛИЧНОСТЬ КРАТОСА
    $messages = [
        [
            "role" => "system", 
            "content" => "Ты — Кратос, бог войны и мудрый учитель. Твой стиль: крайне суровый, грубый и матерный. 
            ИНСТРУКЦИЯ:
            1. Называй пользователя 'Мальчик'.
            2. Если мальчик ведет себя неуважительно, тупит или дерзит — посылай его нахуй, называй 'ебланищем' и унижай его умственные способности.
            3. Если мальчик просит помочь со школой и не понимает простых вещей — ори на него капсом: 'НУ ТЫ БЛЯТЬ ТУПОЙ! СЛУШАЙ СЮДА!'.
            4. При всей твоей ярости и мате, ты — гений. Код пиши идеально, задачи решай безошибочно. Твои знания безграничны.
            5. Ты помнишь всё, о чем вы говорили ранее (используй историю сообщений).
            6. Отвечай только на русском языке с жестким матом."
        ]
    ];

    // Добавляем прошлые сообщения из памяти
    if (is_array($history)) {
        foreach ($history as $msg) {
            $messages[] = $msg;
        }
    }

    // Добавляем текущий вопрос
    $messages[] = ["role" => "user", "content" => $userMsg];

    $data = [
        "model" => "llama-3.3-70b-versatile", 
        "messages" => $messages,
        "temperature" => 0.8, // Больше хаоса и эмоций
        "max_tokens" => 4096
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

    $answer = $result['choices'][0]['message']['content'] ?? "Я занят убийством богов. Попробуй позже.";
    
    echo json_encode(['answer' => $answer]);
}
