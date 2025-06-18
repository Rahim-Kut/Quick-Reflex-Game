<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
$rows = db()->query("SELECT id,player,time_ms,played_at From game_history ORDER BY id DESC LIMIT 30")->fetchAll(PDO::FETCH_ASSOC);

$rows = db()->query("SELECT id,player,time_ms,played_at From game_history ORDER BY id DESC LIMIT 30")->fetchAll(PDO::FETCH_ASSOC);

$resultsDir = __DIR__ . '/results';
foreach (glob("$resultsDir/*.{go,json}", GLOB_BRACE) as $file) {
    unlink($file);
}
?>

<!doctype html>
<html>

<head>
    <title>Quick Reflex</title>
    <link rel="stylesheet" href="static/style.css">
    <script defer src="static/game.js"></script>
</head>

<body>
    <header>
        <h1>Quick Reflex</h1>
        <nav>
            <?php if (user()): ?>
                <span>Hi <?= htmlspecialchars(user()['username']) ?></span>
                <a href="logout.php">Logout</a>
                <?php if (is_admin()): ?>
                    <a href="manage_users.php">Users</a>
                    <a href="settings.php">Settings</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>

    <div id="device-status">
        Device status: <span id="ds-text">checking…</span>
    </div>

    <?php if (user()): ?>
        <button id="startBtn" class="btn">Start</button>
        <button id="restartBtn" class="btn" style="display:none">↻ Play Again</button>
        <div id="statusMessage" class="status-message"></div>
    <?php endif; ?>

    <div id="go">GO!</div>
    <div id="result"></div>

    <form id="saveForm" style="display:none" onsubmit="saveScore(event)">
        <input type="hidden" id="time_ms" name="time_ms">
        <button type="submit" class="btn">Save Score</button>
    </form>

    <div class="card">
        <h2>Hall of Fame</h2>
        <table>
            <tr>
                <th>#</th>
                <th>Player</th>
                <th>Time</th>
                <th>Date</th>
                <?php if (is_admin()) echo '<th>Admin</th>'; ?>
            </tr>
            <?php foreach ($rows as $i => $r): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($r['player']) ?></td>
                    <td><?= $r['time_ms'] ?> ms</td>
                    <td><?= $r['played_at'] ?></td>
                    <?php if (is_admin()): ?>
                        <td class="admin"><a href="delete.php?id=<?= $r['id'] ?>">🗑</a></td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>

</html>