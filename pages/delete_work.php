<?php
require_once '../includes/auth_check.php';
require_once '../config/app.php';
require_once '../config/db.php';

$workId = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $workId > 0) {
    $stmt = $conn->prepare('DELETE FROM works WHERE id = ? AND user_id = ?');
    $stmt->bind_param('ii', $workId, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
}

header('Location: ' . BASE_URL . '/pages/view_works.php');
exit;
