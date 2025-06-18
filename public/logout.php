<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
session_start();
session_destroy();
header('Location: index.php');
