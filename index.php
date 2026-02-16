<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KratosGPT - Призрак Спарты</title>
    <style>
        body { background: #121212; color: #e0e0e0; font-family: 'Verdana', sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { width: 90%; max-width: 500px; background: #1a1a1a; border: 2px solid #8b0000; box-shadow: 0 0 20px rgba(139, 0, 0, 0.5); padding: 20px; border-radius: 8px; }
        h1 { text-align: center; color: #8b0000; letter-spacing: 5px; text-transform: uppercase; margin-top: 0; }
        h1 span { color: #fff; }
        #chat-box { height: 350px; overflow-y: auto; padding: 15px; background: #0a0a0a; border: 1px solid #333; margin-bottom: 15px; display: flex; flex-direction: column; gap: 10px; border-radius: 4px; }
        .msg { padding: 10px; border-radius: 5px; font-size: 14px; line-height: 1.4; max-width: 85%; word-wrap: break-word; }
        .bot { align-self: flex-start; background: #2a0505; border-left: 4px solid #8b0000; color: #ffcccc; }
        .user { align-self: flex-end; background: #333; border-right: 4px solid #777; color: #fff; text-align: right; }
        form { display: flex; gap: 10px; }
        input { flex: 1; padding: 12px; background: #000; border: 1px solid #444; color: #fff; border-radius: 4px; outline: none; }
        input:focus { border-color: #8b0000; }
        button { padding: 12px 20px; background: #8b0000; border: none; color: #fff; font-weight: bold; cursor: pointer; border-radius: 4px; text-transform: uppercase; }
        button:hover { background: #a00000; }
    </style>
</head>
<body>
    <div class="container">
        <h1>KRATOS<span>GPT</span></h1>
        <div id="chat-box">
            <div class="msg bot"><strong>Кратос:</strong> Слушаю тебя, мальчик. Говори по делу.</div>
        </div>
        <form id="chat-form">
            <input type="text" id="user-input" placeholder="Твой вопрос..." required autocomplete="off">
            <button type="submit">УДАР</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#chat-form').on('submit', function(e) {
            e.preventDefault();
            let msg = $('#user-input').val();
            if(!msg.trim()) return;

            $('#chat-box').append('<div class="msg user"><strong>Ты:</strong> ' + msg + '</div>');
            $('#user-input').val('');
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

            $.post('chat.php', {message: msg}, function(data) {
                let text = data.answer ? data.answer : "Я промолчу...";
                $('#chat-box').append('<div class="msg bot"><strong>Кратос:</strong> ' + text + '</div>');
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            }, 'json').fail(function() {
                $('#chat-box').append('<div class="msg bot"><strong>Кратос:</strong> Боги прервали связь. Проверь логи Render.</div>');
            });
        });
    </script>
</body>
</html>
