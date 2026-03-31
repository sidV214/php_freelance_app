<?php
require_once '../includes/auth_check.php';
require_once '../config/app.php';
require_once '../config/db.php';

$title = '';
$description = '';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $userId = $_SESSION['user_id'];

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare('INSERT INTO works (user_id, title, description) VALUES (?, ?, ?)');
        $stmt->bind_param('iss', $userId, $title, $description);

        if ($stmt->execute()) {
            $success = 'Requirement posted successfully.';
            $title = '';
            $description = '';
        } else {
            $errors[] = 'Failed to post requirement. Please try again.';
        }

        $stmt->close();
    }
}

require_once '../includes/header.php';
?>

<div class="editor-shell">
    <div class="editor-panel">
        <span class="section-eyebrow">Create</span>
        <h1 class="editor-panel-title">Post a requirement that is easy to understand.</h1>
        <p class="editor-panel-copy">
            Clear titles and direct descriptions make your work board feel more professional and much easier to browse later.
        </p>
        <div class="editor-tips">
            <div class="editor-tip">Use a short, specific title.</div>
            <div class="editor-tip">Describe the scope, goals, and expected output.</div>
            <div class="editor-tip">Keep the wording simple and searchable.</div>
        </div>
    </div>

    <div class="card section-card">
        <div class="card-body">
            <div class="section-header">
                <div>
                    <span class="section-eyebrow">Requirement Form</span>
                    <h2 class="section-title">Post Requirement</h2>
                    <p class="section-copy">Create a new listing for other users to browse in the works page.</p>
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

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Example: Need a landing page design">
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="7" placeholder="Explain the work clearly, including what needs to be built or delivered."><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Requirement</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
