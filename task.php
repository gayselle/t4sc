<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$taskId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$task = null;
foreach ($tasks as $item) {
    if ($item['id'] === $taskId) {
        $task = $item;
        break;
    }
}

if (!$task) {
    header('Location: home.php');
    exit;
}

$course = find_course($courses, $task['course_id']);

render_head('Task Details');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a class="crumb" href="course.php?id=<?php echo $course ? (int) $course['id'] : 0; ?>">&lt; Back to Course</a>
    <a class="btn btn-outline-secondary btn-sm" href="task-edit.php?id=<?php echo (int) $task['id']; ?>">Edit Task</a>
  </div>

  <h2><?php echo htmlspecialchars($task['name']); ?></h2>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-body p-0">
          <table class="table table-sm mb-0">
            <tbody>
              <tr><th class="text-muted fw-normal ps-3">Course</th><td><?php echo htmlspecialchars($course ? $course['name'] : '—'); ?></td></tr>
              <tr><th class="text-muted fw-normal ps-3">Name</th><td><?php echo htmlspecialchars($task['name']); ?></td></tr>
              <tr><th class="text-muted fw-normal ps-3">Deadline</th><td><?php echo htmlspecialchars($task['deadline']); ?></td></tr>
              <tr><th class="text-muted fw-normal ps-3">Status</th><td><?php echo htmlspecialchars($task['status']); ?></td></tr>
              <tr><th class="text-muted fw-normal ps-3">Priority</th><td><?php echo htmlspecialchars($task['priority']); ?></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Task Description</h5>
      <p class="card-text text-muted"><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
