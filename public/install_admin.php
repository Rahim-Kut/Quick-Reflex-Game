<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

require __DIR__ . '/../includes/db.php';
$pdo = db();

$adminHash = password_hash('admin123', PASSWORD_BCRYPT);

$pdo->exec("INSERT INTO users(username,password_hash,role)
            VALUES('admin', '$adminHash', 'admin')
            ON DUPLICATE KEY UPDATE password_hash = '$adminHash'");

echo "Admin account created/updated.<br>
      Username: <b>admin</b> | Password: <b>admin123</b>";
