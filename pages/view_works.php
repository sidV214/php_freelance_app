<?php
require_once '../includes/auth_check.php';
require_once '../config/app.php';
require_once '../config/db.php';

$search = trim($_GET['search'] ?? '');
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 5;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$countSql = "SELECT COUNT(*) AS total
             FROM works";

if ($search !== '') {
    $countSql .= " WHERE title LIKE ? OR description LIKE ?";
}

$countStmt = $conn->prepare($countSql);

if ($search !== '') {
    $searchTerm = '%' . $search . '%';
    $countStmt->bind_param('ss', $searchTerm, $searchTerm);
}

$countStmt->execute();
$countResult = $countStmt->get_result();
$totalRows = (int) ($countResult->fetch_assoc()['total'] ?? 0);
$totalPages = (int) ceil($totalRows / $limit);
$countStmt->close();

if ($totalPages > 0 && $page > $totalPages) {
    $page = $totalPages;
    $offset = ($page - 1) * $limit;
}

$sql = "SELECT works.id, works.user_id, works.title, works.description, works.created_at, users.name
        FROM works
        INNER JOIN users ON works.user_id = users.id";

if ($search !== '') {
    $sql .= " WHERE works.title LIKE ? OR works.description LIKE ?";
}

$sql .= " ORDER BY works.created_at DESC LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

if ($search !== '') {
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param('ssii', $searchTerm, $searchTerm, $limit, $offset);
} else {
    $stmt->bind_param('ii', $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();

require_once '../includes/header.php';
?>

<div class="card section-card">
    <div class="card-body">
        <div class="section-header">
            <div>
                <span class="section-eyebrow">Browse</span>
                <h1 class="section-title">View Works</h1>
                <p class="section-copy">Search through requirements, review recent posts, and manage the ones you created.</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/pages/home.php" class="soft-button">Back to Home</a>
        </div>

        <form method="GET" action="" class="row g-2 mb-4 work-search">
            <div class="col-md-9">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search by title or description"
                    value="<?php echo htmlspecialchars($search); ?>"
                >
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-primary">Search Works</button>
            </div>
        </form>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="row g-3">
                <?php while ($work = $result->fetch_assoc()): ?>
                    <div class="col-12">
                        <div class="work-card">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div>
                                    <h3 class="h4 mb-2"><?php echo htmlspecialchars($work['title']); ?></h3>
                                    <p class="mb-3"><?php echo nl2br(htmlspecialchars($work['description'])); ?></p>
                                    <div class="work-meta">
                                        Posted by <?php echo htmlspecialchars($work['name']); ?> on
                                        <?php echo htmlspecialchars($work['created_at']); ?>
                                    </div>
                                </div>

                                <?php if ((int) $work['user_id'] === (int) $_SESSION['user_id']): ?>
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo BASE_URL; ?>/pages/edit_work.php?id=<?php echo $work['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <form method="POST" action="<?php echo BASE_URL; ?>/pages/delete_work.php" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo (int) $work['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">
                No works found yet.
            </div>
        <?php endif; ?>

        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                            <a
                                class="page-link"
                                href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"
                            >
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php $stmt->close(); ?>
<?php require_once '../includes/footer.php'; ?>
