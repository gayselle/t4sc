<?php
/**
 * Task Detail View Page
 *
 * Displays comprehensive details for a single task including name, course,
 * deadline, status, priority, and description. Provides edit and delete
 * actions with confirmation dialog. Redirects to home if task not found.
 */

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
<main class="flex-grow-1 p-4 p-md-5 main-content">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <a class="text-muted text-decoration-none" href="course.php?id=<?php echo $course ? (int) $course['id'] : 0; ?>">&lt; Back to Course</a>
    <div class="d-flex gap-2">
      <a class="btn btn-outline-secondary btn-sm" href="task-edit.php?id=<?php echo (int) $task['id']; ?>">Edit Task</a>
      <div x-data="{ confirm: false }" class="d-inline-flex align-items-center gap-2">
        <button type="button" class="btn btn-danger btn-sm" x-show="!confirm" @click="confirm = true">Delete Task</button>
        <template x-if="confirm">
          <span class="d-inline-flex align-items-center gap-2">
            <span class="text-danger fw-medium" style="font-size:14px;">Sure?</span>
            <form action="task-delete.php" method="POST" style="display:inline;">
              <input type="hidden" name="task_id" value="<?php echo (int) $task['id']; ?>">
              <button type="submit" class="btn btn-danger btn-sm">Yes, Delete</button>
            </form>
            <button type="button" class="btn btn-outline-secondary btn-sm" @click="confirm = false">Cancel</button>
          </span>
        </template>
      </div>
    </div>
  </div>

  <h2 class="mb-4"><?php echo htmlspecialchars($task['name']); ?></h2>

  <div class="card shadow-sm mb-4">
    <ul class="list-group list-group-flush">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <strong>Course</strong>
        <span class="text-muted"><?php echo htmlspecialchars($course ? $course['name'] : '—'); ?></span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <strong>Name</strong>
        <span class="text-muted"><?php echo htmlspecialchars($task['name']); ?></span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <strong>Deadline</strong>
        <span class="text-muted"><?php echo htmlspecialchars($task['deadline']); ?></span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <strong>Status</strong>
        <span class="text-muted"><?php echo htmlspecialchars($task['status']); ?></span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <strong>Priority</strong>
        <span class="text-muted"><?php echo htmlspecialchars($task['priority']); ?></span>
      </li>
    </ul>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <h3 class="h5 mb-3">Task Description</h3>
      <p class="text-muted mb-0"><?php echo htmlspecialchars($task['description']); ?></p>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
