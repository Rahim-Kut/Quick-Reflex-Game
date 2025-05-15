<?php
session_start();
require_once __DIR__ . '/../../includes/auth.php';
require_login();

if (!isset($_POST['token'])) {
    http_response_code(400);
    exit("need token");
}

$tk = preg_replace('/\D/', '', $_POST['token']);
$dir = __DIR__ . '/../results';
if (!is_dir($dir)) mkdir($dir, 0777, true);

// write the “.go” flag
file_put_contents("$dir/$tk.go", "GO");

// clean up the JSON so Pi never re-arms on it
$current = __DIR__ . '/current_game.json';
if (file_exists($current)) unlink($current);

header('Content-Type: text/plain');
echo "ok";
