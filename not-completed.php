<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$pending = tasks_by_status($tasks, 'Not Completed');
render_head('Not Completed Tasks');
render_topbar();
render_shell_open();
render_sidebar('not-completed', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <a class="crumb" href="home.php">&lt; Back to Home</a>
  <h2>Not Completed</h2>
  <p class="text-muted mb-3">You have <strong class="text-dark"><?php echo count($pending); ?></strong> uncompleted tasks.</p>

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
            <?php foreach ($pending as $task): ?>
              <?php $course = find_course($courses, $task['course_id']); ?>
              <?php render_task_row($task, $course ? $course['name'] : 'Course'); ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
