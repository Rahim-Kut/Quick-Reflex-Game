<?php
require 'includes/db.php';

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
            echo "User <b>$u</b> created. <a href='login.php'>Login</a>";
            exit;
        } catch (PDOException $e) {
            $err = "Username already taken";
        }
    }
}
?>
<h2>Create account</h2>
<form method="post">
    User <input name="username"><br>
    Pass <input type="password" name="password"><br>
    <button>Register</button>
    <?= isset($err) ? "<p style='color:red'>$err</p>" : "" ?>
</form>