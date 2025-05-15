<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$sql = <<<SQL
SELECT
u.id,
u.username,
u.role,
count(g.id) AS games_played,
COALESCE(MIN(g.time_ms), NULL) AS best_time,
COALESCE(ROUND(AVG(g.time_ms), 2), NULL) AS avg_time
FROM users u
LEFT JOIN game_history g
on g.player = u.username
GROUP BY u.id, u.username, u.role
ORDER BY u.username
SQL;

$users = db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <h1>Manage Users</h1
    <p><a href="index.php">Back to Game</a></p>
    <table>
        <tr>
            <th>#</th>
            <th>Username</th>
            <th>Role</th>
            <th>Games Played</th>
            <th>Best Time</th>
            <th>Average Time</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($users as $i => $u): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['role']) ?></td>
                <td><?= $u['games_played'] ?></td>
                <td><?= $u['best_time'] ?? '—' ?></td>
                <td><?= $u['avg_time'] ?? '—' ?></td>
                <td>
                    <?php if ($u['id'] != user()['id']): ?>
                        <a
                            href="delete_user.php?id=<?= $u['id'] ?>"
                            onclick="return confirm('Delete user <?= htmlspecialchars($u['username']) ?> and all their games?');"
                            >🗑 Delete</a>
                    <?php else: ?>
                        (you)
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>