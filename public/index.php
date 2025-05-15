<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
$rows = db()->query("SELECT id,player,time_ms,played_at From game_history ORDER BY id DESC LIMIT 30")->fetchAll(PDO::FETCH_ASSOC);

$rows = db()->query("SELECT id,player,time_ms,played_at From game_history ORDER BY id DESC LIMIT 30")->fetchAll(PDO::FETCH_ASSOC);

$resultsDir = __DIR__.'/results';
foreach (glob("$resultsDir/*.{go,json}", GLOB_BRACE) as $file){
    unlink($file);
}
?>

<!doctype html>
<html>

<head>
    <title>Quick Reflex</title>
    <link rel="stylesheet" href="static/style.css">
    <script src="static/game.js"></script>
</head>

<body>
    <h1>Quick Reflex</h1>

    <?php
    if (user()): ?>
        Logged in as <?= htmlspecialchars(user()['username']) ?> |
        <a href="logout.php">Logout</a>
        <?php if (is_admin()): ?>
            <a href="manage_users.php">Manage users</a
            <?php endif; ?>
        <br><br>
        <button id="startBtn">Start</button>
        <button id="restartBtn" style="display:none">↻ Play again</button>

        <?php
    else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>

        <div id="go">GO!</div>
        <div id="result"></div>

        <form id="saveForm" style="display:none" onsubmit="saveScore(event)">
            <input type="hidden" id="time_ms" name="time_ms">
            <button>Save</button>
        </form>

        <h2>Hall of Fame</h2>
        <table>
            <?php if (is_admin()) echo '<th>Admin</th>'; ?>

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
            <?php endforeach ?>
        </table>
</body>

</html>