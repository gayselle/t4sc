<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$completed = tasks_by_status($tasks, 'Completed');
render_head('Completed Tasks');
render_topbar();
render_shell_open();
render_sidebar('completed', $courses);
render_sidebar_toggle();
?>
<main class="flex-grow-1 p-4 p-md-5 main-content">
  <a class="text-muted text-decoration-none mb-3 d-inline-block" href="home.php">&lt; Back to Home</a>
  <h2 class="mb-1">Completed</h2>
  <p class="text-muted mb-4">You have <strong class="text-dark"><?php echo count($completed); ?></strong> completed tasks.</p>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr><th>Task</th><th>Course</th><th>Deadline</th><th>Priority</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($completed as $task): ?>
            <?php $course = find_course($courses, $task['course_id']); ?>
            <?php render_task_row($task, $course ? $course['name'] : 'Course'); ?>
          <?php endforeach; ?>
          <?php if (empty($completed)): ?>
            <tr><td colspan="5" class="text-center text-muted py-3">No completed tasks yet.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
