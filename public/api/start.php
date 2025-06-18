<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

session_start();
require_once __DIR__ . '/../../includes/auth.php';
require_login();    

$token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$delay = mt_rand(2000, 5000) / 1000;

file_put_contents('current_game.json', json_encode([
    'token' => $token,
    'delay' => $delay,
]));

header('Content-Type: application/json');
echo json_encode(['token' => $token, 'delay' => $delay,]);
