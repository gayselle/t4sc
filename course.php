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
<main class="flex-grow-1 p-4 p-md-5 main-content">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <a class="text-muted text-decoration-none" href="home.php">&lt; Back to Home</a>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary btn-sm" href="course-edit.php?id=<?php echo (int) $course['id']; ?>">
        Edit Course
      </a>
      <form action="course-delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this course? This cannot be undone.');" style="display:inline;">
        <input type="hidden" name="course_id" value="<?php echo (int) $course['id']; ?>">
        <button type="submit" class="btn btn-danger btn-sm">Delete Course</button>
      </form>
    </div>
  </div>

  <h2 class="mb-1"><?php echo htmlspecialchars($course['name']); ?></h2>
  <p class="text-muted mb-4"><?php echo htmlspecialchars($course['description']); ?></p>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr><th>Task</th><th>Course</th><th>Deadline</th><th>Priority</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($courseTasks as $task): ?>
            <?php render_task_row($task, $course['name']); ?>
          <?php endforeach; ?>
          <?php if (empty($courseTasks)): ?>
            <tr><td colspan="5" class="text-center text-muted py-3">No tasks in this course yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="card-footer d-flex justify-content-end">
      <a class="btn btn-primary btn-sm" href="task-new.php">+ New Task</a>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
