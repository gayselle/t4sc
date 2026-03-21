<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$taskId = isset($_POST['task_id']) ? (int) $_POST['task_id'] : 0;
$redirect = $_POST['redirect'] ?? 'home.php';

// Prevent open redirect: reject absolute URLs and protocol-relative URLs
if (preg_match('#^https?://#i', $redirect) || str_starts_with($redirect, '//')) {
    $redirect = 'home.php';
}

if ($taskId > 0) {
    $userId = (int) $_SESSION['user_id'];
    $newStatus = isset($_POST['completed']) ? 'Completed' : 'Not Completed';

    $stmt = $pdo->prepare(
        'UPDATE task SET task_status = :status WHERE task_id = :task_id AND user_id = :user_id'
    );

    try {
        $stmt->execute([
            ':status' => $newStatus,
            ':task_id' => $taskId,
            ':user_id' => $userId,
        ]);
    } catch (PDOException $e) {
        // silently ignore for now
    }
}

header('Location: ' . $redirect);
exit;

