<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

if (!isset($_POST['token'], $_POST['time_ms'])) {
    http_response_code(400);
    exit("need token & time_ms");
}

$token   = preg_replace('/\D/', '', $_POST['token']);
$time_ms = (int) $_POST['time_ms'];

$dir = __DIR__ . '/../results';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

file_put_contents(
    "$dir/$token.json",
    json_encode(['time_ms' => $time_ms])
);

header('Content-Type: application/json');
echo json_encode(['time_ms' => $time_ms]);
