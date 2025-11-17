<?php

$config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'opensource'
];

$mysqli = new mysqli($config['host'], $config['username'], $config['password'], $config['database']);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit;
}

?>