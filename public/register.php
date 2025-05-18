<?php
require __DIR__ . '/../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');

    if (!$u || !$p) {
        $err = "Please fill in all fields";
    } else {
        $hash = password_hash($p, PASSWORD_BCRYPT);
        try {
            db()->prepare("INSERT INTO users(username,password_hash) VALUES(?, ?)")
                ->execute([$u, $hash]);

            $newId = db()->lastInsertId();
            $_SESSION['user'] = ['id' => $newId, 'username' => $u, 'role' => 'user'];
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $err = "Username already taken";
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Register – Quick Reflex</title>
    <link rel="stylesheet" href="static/style.css">
</head>

<body>
    <header>
        <h1>Quick Reflex</h1>
    </header>

    <div class="card" style="max-width: 400px; margin: auto;">
        <h2>Create Account</h2>
        <?php if (!empty($err)): ?>
            <p style="color:#f87171"><?= htmlspecialchars($err) ?></p>
        <?php endif; ?>
        <form method="post">
            <label>User</label><br>
            <input type="text" name="username" required style="width:100%; padding:8px; margin:6px 0;"><br>
            <label>Password</label><br>
            <input type="password" name="password" required style="width:100%; padding:8px; margin:6px 0;"><br>
            <button class="btn" type="submit">Register</button>
        </form>
        <p style="margin-top:12px; font-size:0.9rem;">
            Already have an account? <a href="login.php" style="color:var(--accent1)">Login here</a>.
        </p>
    </div>

</body>

</html>