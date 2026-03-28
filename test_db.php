<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sigrecette', 'root', '');
    echo 'DB connection OK' . PHP_EOL;
    $stmt = $pdo->query("SHOW TABLES LIKE 'migrations'");
    echo 'migrations table exists: ' . ($stmt->fetch() ? 'yes' : 'no') . PHP_EOL;
} catch (Exception $e) {
    echo 'DB error: ' . $e->getMessage() . PHP_EOL;
}
