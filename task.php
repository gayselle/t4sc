<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

$taskId = isset($_GET['id']) ? (int) $_GET['id'] : 101;
$task = null;
foreach ($tasks as $item) {
    if ($item['id'] === $taskId) {
        $task = $item;
        break;
    }
}
$course = $task ? find_course($courses, $task['course_id']) : null;

render_head('Task Details');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <div class="course-header">
    <a class="crumb" href="course.php?id=<?php echo $course ? $course['id'] : 1; ?>">&lt; Back to Course</a>
    <button class="link-button">Edit Task</button>
  </div>

  <h2><?php echo htmlspecialchars($task ? $task['name'] : 'Task Name'); ?></h2>

  <section class="task-detail">
    <div class="detail-grid">
      <div class="detail-item"><strong>Course</strong><span><?php echo htmlspecialchars($course ? $course['name'] : 'Course Name'); ?></span></div>
      <div class="detail-item"><strong>Name</strong><span><?php echo htmlspecialchars($task ? $task['name'] : 'Task'); ?></span></div>
      <div class="detail-item"><strong>Deadline</strong><span><?php echo htmlspecialchars($task ? $task['deadline'] : 'Date'); ?></span></div>
      <div class="detail-item"><strong>Status</strong><span><?php echo htmlspecialchars($task ? $task['status'] : 'Type'); ?></span></div>
      <div class="detail-item"><strong>Priority</strong><span><?php echo htmlspecialchars($task ? $task['priority'] : 'Type'); ?></span></div>
    </div>

    <section class="panel">
      <h3>Task Description</h3>
      <p><?php echo htmlspecialchars($task ? $task['description'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>
    </section>
  </section>
</main>
<?php
render_shell_close();
?>
