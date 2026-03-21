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
  <div class="course-header">
    <a class="crumb" href="course.php?id=<?php echo $course ? (int) $course['id'] : 0; ?>">&lt; Back to Course</a>
    <a class="link-button" href="task-edit.php?id=<?php echo (int) $task['id']; ?>">
      Edit Task
    </a>
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
