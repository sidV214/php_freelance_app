<?php
require_once '../includes/auth_check.php';
require_once '../config/app.php';
require_once '../config/db.php';

$workId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$title = '';
$description = '';
$errors = [];
$success = '';

if ($workId <= 0) {
    header('Location: ' . BASE_URL . '/pages/view_works.php');
    exit;
}

$stmt = $conn->prepare('SELECT id, user_id, title, description FROM works WHERE id = ?');
$stmt->bind_param('i', $workId);
$stmt->execute();
$result = $stmt->get_result();
$work = $result->fetch_assoc();
$stmt->close();

if (!$work || (int) $work['user_id'] !== (int) $_SESSION['user_id']) {
    header('Location: ' . BASE_URL . '/pages/view_works.php');
    exit;
}

$title = $work['title'];
$description = $work['description'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors[] = 'Title is required.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (empty($errors)) {
        $updateStmt = $conn->prepare('UPDATE works SET title = ?, description = ? WHERE id = ? AND user_id = ?');
        $updateStmt->bind_param('ssii', $title, $description, $workId, $_SESSION['user_id']);

        if ($updateStmt->execute()) {
            $success = 'Work updated successfully.';
        } else {
            $errors[] = 'Failed to update work.';
        }

        $updateStmt->close();
    }
}

require_once '../includes/header.php';
?>

<div class="editor-shell">
    <div class="editor-panel">
        <span class="section-eyebrow">Edit</span>
        <h1 class="editor-panel-title">Refine your post without losing clarity.</h1>
        <p class="editor-panel-copy">
            Update the title and description so the listing stays useful, searchable, and easy to scan in the works feed.
        </p>
        <div class="editor-tips">
            <div class="editor-tip">Keep the title concise and specific.</div>
            <div class="editor-tip">Highlight changes in the description clearly.</div>
            <div class="editor-tip">Save only when the final version feels complete.</div>
        </div>
    </div>

    <div class="card section-card">
        <div class="card-body">
            <div class="section-header">
                <div>
                    <span class="section-eyebrow">Work Editor</span>
                    <h2 class="section-title">Edit Work</h2>
                    <p class="section-copy">Make changes to your post while keeping the same ownership and permissions.</p>
                </div>
                <a href="<?php echo BASE_URL; ?>/pages/view_works.php" class="soft-button">Back to Works</a>
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
                    <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="7"><?php echo htmlspecialchars($description); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update Work</button>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
