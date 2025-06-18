<?php
//  Quick-Reflex Game – Abdulrahim Kuteifan – DT514G VT25

file_put_contents(__DIR__ . '/../heartbeat.json', json_encode(['ts' => time()]));
header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);
