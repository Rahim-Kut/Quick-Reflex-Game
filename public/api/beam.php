<?php

if (!isset($_POST['token'], $_POST['time_ms'])) {
    http_response_code(400);
    exit("need token & time_ms");
}

$token   = preg_replace('/\D/', '', $_POST['token']);  // strip non-digits
$time_ms = (int) $_POST['time_ms'];

// ensure results folder exists
$dir = __DIR__ . '/../results';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

// write out: results/<token>.json
file_put_contents(
    "$dir/$token.json",
    json_encode(['time_ms' => $time_ms])
);

// respond so Pi sees 200 OK
header('Content-Type: application/json');
echo json_encode(['time_ms' => $time_ms]);
