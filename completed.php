<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

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
        <?php render_task_row($task); ?>
      <?php endforeach; ?>
    </div>
    <div class="panel-actions">
      <button class="link-button">See More <span>▲</span></button>
    </div>
  </section>
</main>
<?php
render_shell_close();
?>
