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
<main class="main">
  <a class="crumb" href="home.php">&lt; Back to Home</a>
  <h2>Completed</h2>
  <p class="summary">You have <strong><?php echo count($completed); ?></strong> completed tasks.</p>

  <section class="panel">
    <div class="task-grid">
      <?php foreach ($completed as $task): ?>
        <?php $course = find_course($courses, $task['course_id']); ?>
        <?php render_task_row($task, $course ? $course['name'] : 'Course'); ?>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php
render_shell_close();
?>
