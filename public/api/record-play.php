<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_login();

if (!isset($_POST['time_ms'])) {
    http_response_code(400);
    exit('Missing time_ms');
}

$user = user()['id'];
$time = (int) $_POST['time_ms'];

db()->prepare("INSERT INTO plays (user_id, time_ms) VALUES (?, ?)")
    ->execute([$user, $time]);

header('Content-Type: application/json');
echo json_encode(['ok' => true]);
