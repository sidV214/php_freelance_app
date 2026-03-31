<?php
require_once '../config/app.php';
require_once '../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$name = '';
$email = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '') {
        $errors[] = 'Name is required.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (empty($errors)) {
        $checkStmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $checkStmt->bind_param('s', $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errors[] = 'Email already registered.';
        }

        $checkStmt->close();
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Registration successful. Please log in.';
            header('Location: ' . BASE_URL . '/auth/login.php');
            exit;
        } else {
            $errors[] = 'Something went wrong. Please try again.';
        }

        $stmt->close();
    }
}

require_once '../includes/header.php';
?>

<div class="auth-shell">
    <div class="row align-items-center g-4">
        <div class="col-lg-6">
            <div class="auth-hero">
                <span class="auth-kicker">Create Account</span>
                <h1 class="auth-title">Start building your own mini freelancing workspace.</h1>
                <p class="auth-copy">
                    Register to create posts, explore requirements, and manage your profile in a practical core PHP project that stays easy to understand.
                </p>
                <div class="auth-points">
                    <div class="auth-point">
                        <span class="auth-point-badge">1</span>
                        Clean registration with basic validation
                    </div>
                    <div class="auth-point">
                        <span class="auth-point-badge">2</span>
                        Secure password storage using modern hashing
                    </div>
                    <div class="auth-point">
                        <span class="auth-point-badge">3</span>
                        Direct path into your dashboard after login
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 ms-lg-auto">
            <div class="card auth-card">
                <div class="card-body">
                    <h2 class="auth-card-title">Register</h2>
                    <p class="auth-card-subtitle">Fill in your details to create a new account.</p>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Create Account</button>
                    </form>

                    <div class="text-center mt-4 auth-link-row">
                        Already registered?
                        <a href="<?php echo BASE_URL; ?>/auth/login.php">Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
