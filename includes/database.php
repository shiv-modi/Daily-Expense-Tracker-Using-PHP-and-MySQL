<?php

if (defined('DATABASE_PHP_INCLUDED')) {
    return;
}
define('DATABASE_PHP_INCLUDED', true);

include_once('env_loader.php');

$database_url = getenv('DATABASE_URL');

if ($database_url) {
    $parsed = parse_url($database_url);
    $host = $parsed['host'] ?? '127.0.0.1';
    $port = $parsed['port'] ?? 3306;
    $user = $parsed['user'] ?? 'root';
    $pass = $parsed['pass'] ?? '';
    $dbname = ltrim($parsed['path'] ?? '', '/');
} else {
    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $port = getenv('DB_PORT') ?: 3306;
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';
    $dbname = getenv('DB_NAME') ?: 'expenditure';
}

$db = new mysqli($host, $user, $pass, $dbname, $port);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
?>
