<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$id = intval($_GET['id'] ?? 0);

if ($id === user()['id']) {
    http_response_code(403);
    exit("You cannot delete your own account.");
}

$stmt = db()->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$id]);
$username = $stmt->fetchColumn();

if ($username) {
    db()->prepare("DELETE FROM game_history WHERE player = ?")
        ->execute([$username]);
    db()->prepare("DELETE FROM users WHERE id = ?")
        ->execute([$id]);
}

header('Location: manage_users.php');
exit;
