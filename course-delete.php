<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $courseId = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
  $userId = (int)($_SESSION['user_id'] ?? 0);

  if ($courseId > 0 && $userId > 0) {
    try {
      // Delete tasks first to avoid FK constraint failures.
      $pdo->beginTransaction();

      $stmtTasks = $pdo->prepare(
        'DELETE FROM task WHERE course_id = :course_id AND user_id = :user_id'
      );
      $stmtTasks->execute([
        ':course_id' => $courseId,
        ':user_id' => $userId,
      ]);

      $stmtCourse = $pdo->prepare(
        'DELETE FROM course WHERE course_id = :course_id AND user_id = :user_id'
      );
      $stmtCourse->execute([
        ':course_id' => $courseId,
        ':user_id' => $userId,
      ]);

      if ($stmtCourse->rowCount() > 0) {
        $pdo->commit();
        header('Location: home.php?status=deleted');
        exit;
      }

      $pdo->rollBack();
      header('Location: home.php?error=unauthorized');
      exit;
    } catch (PDOException $e) {
      if ($pdo->inTransaction()) {
        $pdo->rollBack();
      }
      error_log($e->getMessage());
      header('Location: home.php?error=db_error');
      exit;
    }
  }
}

header('Location: home.php');
exit;

