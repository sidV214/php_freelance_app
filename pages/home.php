<?php
require_once '../includes/auth_check.php';
require_once '../config/app.php';
require_once '../includes/header.php';
?>

<div class="row g-4">
    <div class="col-12">
        <div class="card dashboard-hero">
            <div class="card-body p-0">
                <span class="dashboard-kicker">Dashboard</span>
                <h1 class="dashboard-title">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>.</h1>
                <p class="dashboard-copy mb-0">
                    Keep track of requirements, explore posted work, and manage your profile from one clear workspace built for your PHP practice project.
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <a href="<?php echo BASE_URL; ?>/pages/post_requirement.php" class="card action-card action-card-primary">
            <div class="card-body">
                <span class="action-icon action-icon-primary">P</span>
                <h2 class="action-title">Post Requirement</h2>
                <p class="action-copy">
                    Create a new requirement with a clear title and description so others can view it in the work list.
                </p>
                <span class="action-link">Create a post</span>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="<?php echo BASE_URL; ?>/pages/view_works.php" class="card action-card action-card-blue">
            <div class="card-body">
                <span class="action-icon action-icon-blue">V</span>
                <h2 class="action-title">View Works</h2>
                <p class="action-copy">
                    Browse all requirements, search by keywords, and manage your own posts with edit and delete controls.
                </p>
                <span class="action-link">Explore works</span>
            </div>
        </a>
    </div>

    <div class="col-md-4">
        <a href="<?php echo BASE_URL; ?>/pages/profile.php" class="card action-card action-card-slate">
            <div class="card-body">
                <span class="action-icon action-icon-slate">U</span>
                <h2 class="action-title">Profile</h2>
                <p class="action-copy">
                    Review your account details, update your password, and upload a profile photo from one place.
                </p>
                <span class="action-link">Manage profile</span>
            </div>
        </a>
    </div>

    <div class="col-12">
        <div class="text-center pt-2">
            <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="text-danger fw-semibold text-decoration-none">Logout</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
