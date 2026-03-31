<?php
require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer App</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>/assets/styles.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body>
<div class="app-shell">
<nav class="navbar navbar-expand-lg navbar-dark app-navbar">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL . ($isLoggedIn ? '/pages/home.php' : '/auth/login.php'); ?>">Freelancer App</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <div class="navbar-nav ms-auto">
                <?php if ($isLoggedIn): ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/pages/home.php">Home</a>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/pages/view_works.php">View Works</a>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/pages/post_requirement.php">Post Requirement</a>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/pages/profile.php">Profile</a>
                    <a class="nav-link text-warning" href="<?php echo BASE_URL; ?>/auth/logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/auth/login.php">Login</a>
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container page-wrap">
