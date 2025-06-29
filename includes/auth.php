<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 60,  
        'path'     => '/',
        'samesite' => 'Lax'
    ]);
    session_start();
}



function user()
{
    return $_SESSION['user'] ?? null;
}

function is_admin()
{
    return user() && user()['role'] === 'admin';
}

function require_login()
{
    if (!user()) {
        header('Location: /login.php?next=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
}

function require_admin()
{
    if (!is_admin()) {
        http_response_code(403);
        exit("Forbidden");
    }
}
