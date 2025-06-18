<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
session_start();

if (!isset($_POST['time_ms'])) {
    http_response_code(400);
    exit("need time_ms");
}

$user = user()['username'];
$time_ms = (int) $_POST['time_ms'];

db()->prepare(
    "INSERT INTO game_history (player, time_ms) VALUES (?, ?)"
)->execute([$user, $time_ms]);

echo "ok";
