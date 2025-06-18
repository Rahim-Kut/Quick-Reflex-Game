<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $on = isset($_POST['buzzer']);
    file_put_contents(__DIR__ . '/buzzer_enabled.txt', $on ? '1' : '0');
    header('Location: settings.php?ok=1');
    exit;
}

$flag = @file_get_contents(__DIR__ . '/buzzer_enabled.txt');
$buzzer = $flag !== '0';
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Settings – Quick Reflex</title>
    <link rel="stylesheet" href="static/style.css">
</head>

<body>
    <header>
        <h1>Quick Reflex</h1>
        <nav>
            <a href="index.php">Game</a>
            <a href="manage_users.php">Users</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="card" style="max-width: 400px; margin: auto;">
        <h2>Settings</h2>
        <?php if (!empty($msg)): ?>
            <p style="color:#4ade80"><?= htmlspecialchars($msg) ?></p>
        <?php endif; ?>
        <form method="post">
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="buzzer" <?= $buzzer ? 'checked' : '' ?>>
                Enable buzzer on beam-break
            </label><br><br>
            <button class="btn" type="submit">Save Settings</button>
        </form>
    </div>

</body>

</html>