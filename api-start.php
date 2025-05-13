<?php
session_start();
require_once 'includes/auth.php';
require_login();    

$token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
$delay = mt_rand(1000, 5000) / 1000;

file_put_contents('current_game.json', json_encode([
    'token' => $token,
    'delay' => $delay,
]));

header('Content-Type: application/json');
echo json_encode(['token' => $token, 'delay' => $delay,]);
