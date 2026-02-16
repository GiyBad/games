<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KratosGPT - Бог Кода</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Verdana', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { width: 95%; max-width: 800px; background: #1a1a1a; border: 2px solid #8b0000; box-shadow: 0 0 30px rgba(139, 0, 0, 0.5); padding: 20px; border-radius: 8px; display: flex; flex-direction: column; height: 85vh; }
        h1 { text-align: center; color: #8b0000; letter-spacing: 5px; text-transform: uppercase; margin: 0 0 15px 0; }
        #chat-box { flex: 1; overflow-y: auto; padding: 15px; background: #0a0a0a; border: 1px solid #333; margin-bottom: 15px; display: flex; flex-direction: column; gap: 15px; border-radius: 4px; }
        .msg { padding: 12px; border-radius: 5px; font-size: 14px; line-height: 1.5; max-width: 85%; word-wrap: break-word; white-space: pre-wrap; }
        .bot { align-self: flex-start; background: #2a0505; border-left: 4px solid #8b0000; color: #ffcccc; font-family: 'Courier New', monospace; }
        .user { align-self: flex-end; background: #333; border-right: 4px solid #777; color: #fff; }
        form { display: flex; gap: 10px; }
        input { flex: 1; padding: 15px; background: #000; border: 1px solid #444; color: #fff; border-radius: 4px; outline: none; font-size: 16px; }
        button { padding: 15px 25px; background: #8b0000; border: none; color: #fff; font-weight: bold; cursor: pointer; border-radius: 4px; }
        button:disabled { background: #444; }
        code { background: #000; color: #0f0; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>KRATOS<span>GPT</span></h1>
        <div id="chat-box">
            <div class="msg bot"><strong>Кратос:</strong> Мальчик, я готов. Какую задачу нам нужно сокрушить сегодня?</div>
        </div>
        <form id="chat-form">
            <input type="text" id="user-input" placeholder="Напиши задачу или код..." required autocomplete="off">
            <button type="submit" id="send-btn">УДАР</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Инициализируем историю сообщений (Контекст)
        let chatHistory = [];

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            let msg = $('#user-input').val().trim();
            if(!msg) return;

            // Добавляем в интерфейс
            $('#chat-box').append('<div class="msg user"><strong>Ты:</strong> ' + msg + '</div>');
            $('#user-input').val('');
            $('#send-btn').prop('disabled', true);
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

            // Отправляем запрос с историей
            $.ajax({
                url: 'chat.php',
                method: 'POST',
                data: { 
                    message: msg,
                    history: JSON.stringify(chatHistory) // Отправляем всю прошлую переписку
                },
                dataType: 'json',
                success: function(data) {
                    let answer = data.answer;
                    $('#chat-box').append('<div class="msg bot"><strong>Кратос:</strong> ' + answer + '</div>');
                    
                    // Обновляем историю (запоминаем этот раунд)
                    chatHistory.push({ role: "user", content: msg });
                    chatHistory.push({ role: "assistant", content: answer });
                    
                    // Держим историю не бесконечной (последние 10 сообщений), чтобы не тормозило
                    if (chatHistory.length > 20) chatHistory.splice(0, 2);

                    $('#send-btn').prop('disabled', false);
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                },
                error: function() {
                    $('#chat-box').append('<div class="msg bot"><strong>Кратос:</strong> Боги разорвали связь...</div>');
                    $('#send-btn').prop('disabled', false);
                }
            });
        });
    </script>
</body>
</html>
