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
  <div class="course-header" style="display: flex; align-items: center; gap: 10px;">
    <a class="crumb" href="course.php?id=<?php echo $course ? (int) $course['id'] : 0; ?>">&lt; Back to Course</a>
    
    <div style="margin-left: auto; display: flex; gap: 8px;">
      <a class="link-button" href="task-edit.php?id=<?php echo (int) $task['id']; ?>">
        Edit Task
      </a>

      <form action="task-delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this task? This cannot be undone.');" style="display: inline;">
        <input type="hidden" name="task_id" value="<?php echo (int) $task['id']; ?>">
        <button type="submit" class="delete-btn">
          Delete Task
        </button>
      </form>
    </div>
  </div>

  <h2><?php echo htmlspecialchars($task['name']); ?></h2>

  <section class="task-detail">
    <div class="detail-grid">
      <div class="detail-item"><strong>Course</strong><span><?php echo htmlspecialchars($course ? $course['name'] : '—'); ?></span></div>
      <div class="detail-item"><strong>Name</strong><span><?php echo htmlspecialchars($task['name']); ?></span></div>
      <div class="detail-item"><strong>Deadline</strong><span><?php echo htmlspecialchars($task['deadline']); ?></span></div>
      <div class="detail-item"><strong>Status</strong><span><?php echo htmlspecialchars($task['status']); ?></span></div>
      <div class="detail-item"><strong>Priority</strong><span><?php echo htmlspecialchars($task['priority']); ?></span></div>
    </div>

    <section class="panel">
      <h3>Task Description</h3>
      <p><?php echo htmlspecialchars($task['description']); ?></p>
    </section>
  </section>
</main>
<?php
render_shell_close();
?>
