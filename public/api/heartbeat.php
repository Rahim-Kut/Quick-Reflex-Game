<?php
// Simply overwrite heartbeat.json with the current timestamp
file_put_contents(__DIR__ . '/../heartbeat.json', json_encode(['ts' => time()]));
header('Content-Type: application/json');
echo json_encode(['status' => 'ok']);

    