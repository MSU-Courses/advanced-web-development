<?php

$conn = mysqli_connect('localhost', 'root', '', 'test_app');
mysqli_set_charset($conn, 'utf8mb4');

$result = mysqli_query($conn, 'SELECT id, name, email FROM users');

while ($user = mysqli_fetch_assoc($result)) {
    echo "{$user['id']}. {$user['name']} ({$user['email']})" . PHP_EOL;
}

mysqli_close($conn);
