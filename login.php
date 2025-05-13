<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = $_POST['username'];
    $p = $_POST['password'];
    //var_dump("typed user = [$u]", "typed pass = [$p]");
    $pdo = db();
    $stmt = $pdo->prepare("Select * from users where username =?");
    $stmt->execute([$u]);
    $user = $stmt->fetch();

    // debug
    //var_dump($user);
    //var_dump(password_verify($p, $user['password_hash'] ?? ''));
    //exit;


    if ($user && password_verify($p, $user['password_hash'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'username' => $user['username'], 'role' => $user['role']];
        header('Location: index.php');
        exit;
    }
    $err = "Wrong credentials";
}
?>
<form method="post">
    User <input name="username"><br>
    Password <input type="password" name="password"><br>
    <button>Login</button>
    <?= isset($err) ? "<p style='color:red'>$err</p>" : "" ?>
</form>