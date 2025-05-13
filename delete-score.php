<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

require_admin();

$id = intval($_GET['id'] ?? 0);
db()->prepare("DELETE FROM game_history WHERE id = ?")->execute([$id]);

header('Location: index.php');
