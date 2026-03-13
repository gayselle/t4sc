<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

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
    $stmt = $pdo->prepare(
      'INSERT INTO course (course_name, course_desc) VALUES (:course_name, :course_desc)'
    );

    try {
      $stmt->execute([
        ':course_name' => $course_name,
        ':course_desc' => $course_desc,
      ]);

      $newCourseId = (int) $pdo->lastInsertId();
      header('Location: course.php?id=' . $newCourseId);
      exit;
    } catch (PDOException $e) {
      $errors[] = 'Could not save course. Please try again.';
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
  <a class="crumb" href="/home.php">&times; Cancel</a>
  <h2 class="form-title">New Course</h2>

  <section class="panel form-panel">
    <?php if (!empty($errors)): ?>
      <p style="margin-bottom:16px; color:#b91c1c; text-align:left; font-size:14px;">
        <?php echo htmlspecialchars(implode(' ', $errors)); ?>
      </p>
    <?php endif; ?>

    <form class="form-grid" method="post" action="course-new.php">
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
        <button class="solid" type="submit">Save</button>
      </div>
    </form>
  </section>
</main>
<?php
render_shell_close();
?>
