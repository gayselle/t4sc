<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

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
  <p class="summary">You have <strong><?php echo count($pending); ?></strong> uncompleted tasks.</p>

  <section class="panel">
    <div class="task-grid">
      <?php foreach ($pending as $task): ?>
        <?php $course = find_course($courses, $task['course_id']); ?>
        <?php render_task_row($task, $course ? $course['name'] : 'Course'); ?>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php
render_shell_close();
?>
