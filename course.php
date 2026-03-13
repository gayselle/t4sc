<?php
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

$courseId = isset($_GET['id']) ? (int) $_GET['id'] : 1;
$course = find_course($courses, $courseId);
$courseTasks = $course ? tasks_for_course($tasks, $course['id']) : [];

render_head('Course Overview');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <div class="hero"></div>

  <div class="course-header">
    <a class="crumb" href="home.php">&lt; Back to Home</a>
    <button class="link-button">Edit Course</button>
  </div>

  <h2><?php echo htmlspecialchars($course ? $course['name'] : 'Course Name'); ?></h2>
  <p><?php echo htmlspecialchars($course ? $course['description'] : 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'); ?></p>

  <section class="panel" style="margin-top: 2rem;">
    <div class="task-grid">
      <?php foreach ($courseTasks as $task): ?>
        <?php render_task_row($task); ?>
      <?php endforeach; ?>
    </div>
    <div class="panel-actions">
      <button class="link-button"><span>+</span> New Task</button>
    </div>
  </section>
</main>
<?php
render_shell_close();
?>
