<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $taskId = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;
  $userId = (int)($_SESSION['user_id'] ?? 0);

  if ($taskId > 0 && $userId > 0) {
    // Optional UX: redirect back to the task's course if we can find it.
    $courseId = 0;
    foreach ($tasks as $item) {
      if ((int)$item['id'] === $taskId) {
        $courseId = (int)($item['course_id'] ?? 0);
        break;
      }
    }

    try {
      // Verify ownership using user_id and task_id.
      $stmt = $pdo->prepare(
        'DELETE FROM task WHERE task_id = :task_id AND user_id = :user_id'
      );

      $stmt->execute([
        ':task_id' => $taskId,
        ':user_id' => $userId,
      ]);

      if ($stmt->rowCount() > 0) {
        $redirect = $courseId > 0 ? ('course.php?id=' . $courseId) : 'home.php';
        header('Location: ' . $redirect . '?status=deleted');
        exit;
      }

      header('Location: home.php?error=unauthorized');
      exit;
    } catch (PDOException $e) {
      error_log($e->getMessage());
      header('Location: home.php?error=db_error');
      exit;
    }
  }
}

// Fallback redirect if accessed incorrectly
header('Location: home.php');
exit;