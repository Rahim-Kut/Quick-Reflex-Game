<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
session_start();
require_admin();

$id = intval($_GET['id'] ?? 0);
if ($id) {
    db()->prepare("DELETE FROM plays WHERE user_id = ?")
        ->execute([$id]);
}

header('Location: manage_users.php');
exit;
