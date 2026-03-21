<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$courseId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$course = find_course($courses, $courseId);

if (!$course) {
    header('Location: home.php');
    exit;
}

$courseTasks = tasks_for_course($tasks, $course['id']);

render_head('Course Overview');
render_topbar();
render_shell_open();
render_sidebar('', $courses, $course['id']);
render_sidebar_toggle();
?>
<main class="main">
  <div class="hero"></div>

  <div class="course-header">
    <a class="crumb" href="home.php">&lt; Back to Home</a>
    <div style="margin-left: auto; display: flex; gap: 8px;">
      <a class="link-button" href="course-edit.php?id=<?php echo (int) $course['id']; ?>">
        Edit Course
      </a>

      <form action="course-delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This cannot be undone.');" style="display: inline;">
        <input type="hidden" name="course_id" value="<?php echo (int) $course['id']; ?>">
        <button type="submit" class="delete-btn">
          Delete Course
        </button>
      </form>
    </div>
  </div>

  <h2><?php echo htmlspecialchars($course['name']); ?></h2>
  <p><?php echo htmlspecialchars($course['description']); ?></p>

  <section class="panel" style="margin-top: 2rem;">
    <div class="task-grid">
      <?php foreach ($courseTasks as $task): ?>
        <?php render_task_row($task, $course['name']); ?>
      <?php endforeach; ?>
    </div>
    <div class="panel-actions">
      <a class="link-button" href="task-new.php">
        <span>+</span> New Task
      </a>
    </div>
  </section>
</main>
<?php
render_shell_close();
?>
