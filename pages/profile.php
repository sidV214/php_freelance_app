<?php
require_once '../includes/auth_check.php';
require_once '../config/app.php';
require_once '../config/db.php';

$userId = $_SESSION['user_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($currentPassword === '') {
        $errors[] = 'Current password is required.';
    }

    if ($newPassword === '') {
        $errors[] = 'New password is required.';
    } elseif (strlen($newPassword) < 6) {
        $errors[] = 'New password must be at least 6 characters.';
    }

    if ($confirmPassword === '') {
        $errors[] = 'Confirm password is required.';
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = 'New password and confirm password must match.';
    }

    if (empty($errors)) {
        $passwordStmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
        $passwordStmt->bind_param('i', $userId);
        $passwordStmt->execute();
        $passwordResult = $passwordStmt->get_result();
        $passwordRow = $passwordResult->fetch_assoc();
        $passwordStmt->close();

        if (!$passwordRow || !password_verify($currentPassword, $passwordRow['password'])) {
            $errors[] = 'Current password is incorrect.';
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
        $updateStmt->bind_param('si', $hashedPassword, $userId);

        if ($updateStmt->execute()) {
            $success = 'Password changed successfully.';
        } else {
            $errors[] = 'Failed to change password.';
        }

        $updateStmt->close();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload_image'])) {
    if (!isset($_FILES['profile_image']) || $_FILES['profile_image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Please choose an image to upload.';
    } else {
        $file = $_FILES['profile_image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024;

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Image upload failed.';
        } elseif (!in_array(mime_content_type($file['tmp_name']), $allowedTypes, true)) {
            $errors[] = 'Only JPG, PNG, and GIF images are allowed.';
        } elseif ($file['size'] > $maxFileSize) {
            $errors[] = 'Image size must be 2MB or less.';
        }

        if (empty($errors)) {
            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $newFileName = 'profile_' . $userId . '_' . time() . '.' . $extension;
            $uploadPath = '../uploads/' . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $imageStmt = $conn->prepare('UPDATE users SET profile_image = ? WHERE id = ?');
                $imageStmt->bind_param('si', $newFileName, $userId);

                if ($imageStmt->execute()) {
                    $success = 'Profile image uploaded successfully.';
                } else {
                    $errors[] = 'Failed to save profile image.';
                }

                $imageStmt->close();
            } else {
                $errors[] = 'Failed to move uploaded image.';
            }
        }
    }
}

$userStmt = $conn->prepare('SELECT name, email, profile_image, created_at FROM users WHERE id = ?');
$userStmt->bind_param('i', $userId);
$userStmt->execute();
$userResult = $userStmt->get_result();
$user = $userResult->fetch_assoc();
$userStmt->close();

require_once '../includes/header.php';
?>

<div class="section-header mb-4">
    <div>
        <span class="section-eyebrow">Account</span>
        <h1 class="section-title">Profile</h1>
        <p class="section-copy">Manage your personal details, profile image, and password from one polished settings page.</p>
    </div>
    <a href="<?php echo BASE_URL; ?>/pages/home.php" class="soft-button">Back to Home</a>
</div>

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

<div class="profile-grid">
    <div class="profile-summary">
        <?php if (!empty($user['profile_image'])): ?>
            <img
                src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($user['profile_image']); ?>"
                alt="Profile Image"
                class="profile-avatar"
            >
        <?php else: ?>
            <div class="profile-avatar avatar-placeholder">
                <?php echo strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
            </div>
        <?php endif; ?>

        <h2 class="section-title mt-4 mb-2"><?php echo htmlspecialchars($user['name'] ?? ''); ?></h2>
        <p class="section-copy mb-0"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>

        <div class="profile-stat">
            <strong>Joined:</strong>
            <?php echo htmlspecialchars($user['created_at'] ?? ''); ?>
        </div>

        <div class="profile-stat">
            <strong>Status:</strong>
            Active account
        </div>
    </div>

    <div class="d-grid gap-4">
        <div class="card profile-panel">
            <div class="card-body">
                <span class="section-eyebrow">Image</span>
                <h3 class="section-title">Upload Profile Image</h3>
                <p class="section-copy mb-4">Choose a clear image in JPG, PNG, or GIF format up to 2MB.</p>

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="profile_image" class="form-label">Choose Image</label>
                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept=".jpg,.jpeg,.png,.gif">
                    </div>

                    <button type="submit" name="upload_image" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
        </div>

        <div class="card profile-panel">
            <div class="card-body">
                <span class="section-eyebrow">Security</span>
                <h3 class="section-title">Change Password</h3>
                <p class="section-copy mb-4">Update your password with your current password for verification.</p>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>

                    <div class="mb-4">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>

                    <button type="submit" name="change_password" class="btn btn-primary">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
