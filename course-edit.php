<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

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
<main class="main">
  <a class="crumb" href="javascript:history.back()">&times; Cancel</a>
  <h2 class="form-title">Edit Course</h2>

  <section class="panel form-panel">
    <?php if (!empty($errors)): ?>
      <p style="margin-bottom:16px; color:#b91c1c; text-align:left; font-size:14px;">
        <?php echo htmlspecialchars(implode(' ', $errors)); ?>
      </p>
    <?php endif; ?>

    <?php if ($course): ?>
      <form class="form-grid" method="post" action="course-edit.php?id=<?php echo (int) $courseId; ?>">
        <label class="labelled">
          Course Name
          <input
            type="text"
            name="course_name"
            placeholder="Course Name"
            value="<?php echo htmlspecialchars($course_name); ?>"
            required
          >
        </label>
        <label class="labelled">
          Course Description
          <textarea
            name="course_desc"
            placeholder="Course Description"
          ><?php echo htmlspecialchars($course_desc); ?></textarea>
        </label>
        <div class="form-actions">
          <button class="solid" type="submit">Save Changes</button>
        </div>
      </form>
    <?php endif; ?>
  </section>
</main>
<?php
render_shell_close();
?>

