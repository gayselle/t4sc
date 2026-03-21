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
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <div class="hero"></div>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <a class="crumb" href="home.php">&lt; Back to Home</a>
    <a class="btn btn-outline-secondary btn-sm" href="course-edit.php?id=<?php echo (int) $course['id']; ?>">Edit Course</a>
  </div>

  <h2><?php echo htmlspecialchars($course['name']); ?></h2>
  <p class="text-muted mb-4"><?php echo htmlspecialchars($course['description']); ?></p>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Task</th>
              <th>Course</th>
              <th>Deadline</th>
              <th>Priority</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($courseTasks as $task): ?>
              <?php render_task_row($task, $course['name']); ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card-footer text-end">
      <a class="btn btn-primary btn-sm" href="task-new.php">+ New Task</a>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
