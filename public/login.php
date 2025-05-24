<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ .   '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = $_POST['username'];
    $p = $_POST['password'];
    //var_dump("typed user = [$u]", "typed pass = [$p]");
    $pdo = db();
    $stmt = $pdo->prepare("Select * from users where username =?");
    $stmt->execute([$u]);
    $user = $stmt->fetch();


    if ($user && password_verify($p, $user['password_hash'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'username' => $user['username'], 'role' => $user['role']];
        header('Location: index.php');
        exit;
    }
    $err = "Wrong credentials";
}
?>

<!doctype html>
<html>
<meta charset="utf-8">
<title>Login - Quick Reflex</title>
<link rel="stylesheet" href="static/style.css">
</head>

<header>
    <h1>Quick Reflex</h1>
</header>

<body>

    <div class="card" style="max-width: 400px; margin: auto;">
        <h2>Login</h2>
        <?php if (!empty($err)): ?>
            <p style="color:#f87171"><?= htmlspecialchars($err) ?></p>
        <?php endif; ?>
        <form method="post">
            <label>User</label><br>
            <input type="text" name="username" required style="width:100%; padding:8px; margin:6px 0;"><br>
            <label>Password</label><br>
            <input type="password" name="password" required style="width:100%; padding:8px; margin:6px 0;"><br>
            <button class="btn" type="submit">Login</button>
        </form>
        <p style="margin-top:12px; font-size:0.9rem;">
            No account? <a href="register.php" style="color:var(--accent1)">Register here</a>.
        </p>
    </div>

</body>

</html>