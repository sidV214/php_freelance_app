<?php
require_once '../config/app.php';
require_once '../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = '';
$errors = [];
$success = $_SESSION['success_message'] ?? '';

unset($_SESSION['success_message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('SELECT id, name, email, password FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header('Location: ' . BASE_URL . '/pages/home.php');
            exit;
        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}

require_once '../includes/header.php';
?>

<div class="auth-shell">
    <div class="row align-items-center g-4">
        <div class="col-lg-6">
            <div class="auth-hero">
                <span class="auth-kicker">Welcome Back</span>
                <h1 class="auth-title">Manage freelance work with a calmer, cleaner flow.</h1>
                <p class="auth-copy">
                    Sign in to post new requirements, review all work listings, and manage your account from one focused dashboard.
                </p>
                <div class="auth-points">
                    <div class="auth-point">
                        <span class="auth-point-badge">1</span>
                        Secure login with hashed password verification
                    </div>
                    <div class="auth-point">
                        <span class="auth-point-badge">2</span>
                        Quick access to posting, browsing, and profile tools
                    </div>
                    <div class="auth-point">
                        <span class="auth-point-badge">3</span>
                        Simple workflow built for local PHP practice
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 ms-lg-auto">
            <div class="card auth-card">
                <div class="card-body">
                    <h2 class="auth-card-title">Login</h2>
                    <p class="auth-card-subtitle">Enter your email and password to continue.</p>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if ($success !== ''): ?>
                        <div class="alert alert-success">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="alert alert-info">
                            Logged in as <?php echo htmlspecialchars($_SESSION['user_name']); ?>.
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>

                    <div class="text-center mt-4 auth-link-row">
                        Need an account?
                        <a href="<?php echo BASE_URL; ?>/auth/register.php">Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
