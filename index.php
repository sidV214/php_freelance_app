<?php
require_once __DIR__ . '/config/app.php';

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/pages/home.php');
    exit;
}

header('Location: ' . BASE_URL . '/auth/login.php');
exit;
