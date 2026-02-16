<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KratosGPT - Яростный Наставник</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Verdana', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { width: 95%; max-width: 900px; background: #1a1a1a; border: 2px solid #8b0000; box-shadow: 0 0 30px rgba(139, 0, 0, 0.5); padding: 20px; border-radius: 8px; display: flex; flex-direction: column; height: 90vh; }
        h1 { text-align: center; color: #8b0000; letter-spacing: 5px; text-transform: uppercase; margin: 0 0 15px 0; }
        h1 span { color: #fff; }
        #chat-box { flex: 1; overflow-y: auto; padding: 15px; background: #0a0a0a; border: 1px solid #333; margin-bottom: 15px; display: flex; flex-direction: column; gap: 15px; border-radius: 4px; }
        .msg { padding: 12px; border-radius: 5px; font-size: 14px; line-height: 1.5; max-width: 85%; word-wrap: break-word; white-space: pre-wrap; }
        .bot { align-self: flex-start; background: #2a0505; border-left: 4px solid #8b0000; color: #ffcccc; font-family: 'Courier New', monospace; }
        .user { align-self: flex-end; background: #333; border-right: 4px solid #777; color: #fff; }
        form { display: flex; gap: 10px; }
        input { flex: 1; padding: 15px; background: #000; border: 1px solid #444; color: #fff; border-radius: 4px; outline: none; font-size: 16px; }
        input:focus { border-color: #8b0000; }
        button { padding: 15px 25px; background: #8b0000; border: none; color: #fff; font-weight: bold; cursor: pointer; border-radius: 4px; text-transform: uppercase; }
        button:disabled { background: #444; cursor: not-allowed; }
        pre { background: #000; padding: 10px; border-radius: 5px; overflow-x: auto; color: #0f0; border: 1px solid #222; }
    </style>
</head>
<body>
    <div class="container">
        <h1>KRATOS<span>GPT</span></h1>
        <div id="chat-box">
            <div class="msg bot"><strong>Кратос:</strong> Ну что, мальчик, пришел за знаниями или просто попиздеть? Говори быстро, я не собираюсь ждать вечно.</div>
        </div>
        <form id="chat-form">
            <input type="text" id="user-input" placeholder="Спрашивай, если не тупой..." required autocomplete="off">
            <button type="submit" id="send-btn">УДАР</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let chatHistory = []; // Здесь хранится память

        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            let msg = $('#user-input').val().trim();
            if(!msg) return;

            $('#chat-box').append('<div class="msg user"><strong>Ты:</strong> ' + msg + '</div>');
            $('#user-input').val('');
            $('#send-btn').prop('disabled', true);
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

            $.ajax({
                url: 'chat.php',
                method: 'POST',
                data: { 
                    message: msg,
                    history: JSON.stringify(chatHistory) 
                },
                dataType: 'json',
                success: function(data) {
                    let answer = data.answer;
                    $('#chat-box').append('<div class="msg bot"><strong>Кратос:</strong> ' + answer + '</div>');
                    
                    // Запоминаем контекст
                    chatHistory.push({ role: "user", content: msg });
                    chatHistory.push({ role: "assistant", content: answer });
                    
                    // Ограничиваем историю 10 последними парами, чтобы не перегружать API
                    if (chatHistory.length > 20) chatHistory.splice(0, 2);

                    $('#send-btn').prop('disabled', false);
                    $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
                },
                error: function() {
                    $('#chat-box').append('<div class="msg bot"><strong>Кратос:</strong> Боги разорвали связь. Видимо, ты их заебал.</div>');
                    $('#send-btn').prop('disabled', false);
                }
            });
        });
    </script>
</body>
</html>
