<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$sql = <<<SQL
SELECT 
  u.id,
  u.username,
  u.role,
  COUNT(pl.id)            AS games_played,
  MIN(pl.time_ms)         AS best_time,
  ROUND(AVG(pl.time_ms),2) AS avg_time
FROM users u
LEFT JOIN plays pl ON pl.user_id = u.id
GROUP BY u.id, u.username, u.role
ORDER BY u.username
SQL;

$users = db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Manage Users – Quick Reflex</title>
    <link rel="stylesheet" href="static/style.css">
</head>

<body>
    <header>
        <h1>Quick Reflex</h1>
        <nav>
            <a href="index.php">Game</a>
            <a href="settings.php">Settings</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <div class="card">
        <h2>Manage Users</h2>
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
                    <td><?= $u['best_time']  ?? '—' ?></td>
                    <td><?= $u['avg_time']   ?? '—' ?></td>
                    <td>
                        <div class="action-buttons">
                            <a
                                href="clear-plays.php?id=<?= $u['id'] ?>"
                                onclick="return confirm(
                                    'Remove ALL play records for <?= htmlspecialchars($u['username']) ?>?'
                                );"
                                class="btn btn-small btn-clear">Clear Plays</a>
                            <?php if ($u['id'] != user()['id']): ?>
                                <a
                                    href="delete_user.php?id=<?= $u['id'] ?>"
                                    onclick="return confirm(
                  'Delete user <?= htmlspecialchars($u['username']) ?> and all their games?'
                );"
                                    class="btn btn-small btn-delete">🗑Delete
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>

</html>