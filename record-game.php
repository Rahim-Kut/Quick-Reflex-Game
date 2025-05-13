<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
session_start();
require_login();

if (!isset($_POST['time_ms'])) {
    http_response_code(400);
    exit("need time_ms");
}

// grab the logged‐in user’s name from the session
$user = user()['username'];
$time_ms = (int) $_POST['time_ms'];

// insert into the database
db()->prepare(
    "INSERT INTO game_history (player, time_ms) VALUES (?, ?)"
)->execute([$user, $time_ms]);

// tell the client it worked
echo "ok";
