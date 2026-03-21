<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$errors = [];
$course_name = '';
$course_desc = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $course_name = trim($_POST['course_name'] ?? '');
  $course_desc = trim($_POST['course_desc'] ?? '');

  if ($course_name === '') {
    $errors[] = 'Course name is required.';
  }

  if (empty($errors)) {
    if (!isset($_SESSION['user_id'])) {
      $errors[] = 'You must be logged in to create a course.';
    } else {
      $userId = (int) $_SESSION['user_id'];

      $stmt = $pdo->prepare(
        'INSERT INTO course (course_name, course_desc, user_id) VALUES (:course_name, :course_desc, :user_id)'
      );

      try {
        $stmt->execute([
          ':course_name' => $course_name,
          ':course_desc' => $course_desc,
          ':user_id' => $userId,
        ]);

        $newCourseId = (int) $pdo->lastInsertId();
        header('Location: course.php?id=' . $newCourseId);
        exit;
      } catch (PDOException $e) {
        $errors[] = 'Could not save course. Please try again.';
      }
    }
  }
}

render_head('New Course');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <a class="crumb" href="javascript:history.back()">&times; Cancel</a>
  <h2>New Course</h2>

  <div class="card" style="max-width: 600px;">
    <div class="card-body">
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger py-2"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
      <?php endif; ?>

      <form method="post" action="course-new.php">
        <div class="mb-3">
          <label class="form-label fw-medium">Course Name</label>
          <input class="form-control" type="text" name="course_name" placeholder="Course Name" value="<?php echo htmlspecialchars($course_name); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-medium">Course Description</label>
          <textarea class="form-control" name="course_desc" placeholder="Course Description" rows="4"><?php echo htmlspecialchars($course_desc); ?></textarea>
        </div>

        <div class="d-flex justify-content-end">
          <button class="btn btn-primary" type="submit">Save</button>
        </div>
      </form>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
