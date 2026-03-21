<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$errors = [];

$courseId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$course = $courseId > 0 ? find_course($courses, $courseId) : null;

if (!$course) {
  $errors[] = 'Course not found or you do not have access to it.';
}

$course_name = $course ? $course['name'] : '';
$course_desc = $course ? $course['description'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $course) {
  $course_name = trim($_POST['course_name'] ?? '');
  $course_desc = trim($_POST['course_desc'] ?? '');

  if ($course_name === '') {
    $errors[] = 'Course name is required.';
  }

  if (empty($errors)) {
    if (!isset($_SESSION['user_id'])) {
      $errors[] = 'You must be logged in to edit a course.';
    } else {
      $userId = (int) $_SESSION['user_id'];

      $stmt = $pdo->prepare(
        'UPDATE course
         SET course_name = :course_name,
             course_desc = :course_desc
         WHERE course_id = :course_id AND user_id = :user_id'
      );

      try {
        $stmt->execute([
          ':course_name' => $course_name,
          ':course_desc' => $course_desc,
          ':course_id' => $courseId,
          ':user_id' => $userId,
        ]);

        header('Location: course.php?id=' . $courseId);
        exit;
      } catch (PDOException $e) {
        $errors[] = 'Could not update course. Please try again.';
      }
    }
  }
}

render_head('Edit Course');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="flex-grow-1 p-4 p-md-5 main-content">
  <a class="text-muted text-decoration-none mb-3 d-inline-block" href="javascript:history.back()">&times; Cancel</a>
  <h2 class="mb-4">Edit Course</h2>

  <div class="card shadow-sm" style="max-width:680px;">
    <div class="card-body p-4">
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
      <?php endif; ?>

      <?php if ($course): ?>
        <form method="post" action="course-edit.php?id=<?php echo (int) $courseId; ?>">
          <div class="mb-3">
            <label class="form-label fw-medium">Course Name</label>
            <input type="text" class="form-control" name="course_name" placeholder="Course Name" value="<?php echo htmlspecialchars($course_name); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-medium">Course Description</label>
            <textarea class="form-control" name="course_desc" placeholder="Course Description" style="min-height:120px;"><?php echo htmlspecialchars($course_desc); ?></textarea>
          </div>
          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>

