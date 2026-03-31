<?php

require_once __DIR__ . '/env.php';

$baseUrl = getenv('APP_BASE_URL');

if ($baseUrl === false || $baseUrl === null) {
    $baseUrl = '/php_freelance_app';
}

define('BASE_URL', rtrim($baseUrl, '/'));
