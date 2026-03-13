<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

$currentDate = new DateTime();
$today = date('Y-m-d');
$dueToday = tasks_due_today($tasks, $today);

render_head('T4SC Dashboard');
render_topbar();
render_shell_open();
render_sidebar('home', $courses);
render_sidebar_toggle();
?>

<main class="main">
  <h2><?php echo $demoUser['greeting']; ?>, <?php echo htmlspecialchars($demoUser['name']); ?>!</h2>
  <p class="summary"><strong>You have <?php echo count($dueToday); ?> tasks due today.</strong></p>

  <section class="panel summary">
    <div class="task-grid">
      <?php foreach ($dueToday as $task): ?>
        <?php $course = find_course($courses, $task['course_id']); ?>
        <?php render_task_row($task, $course ? $course['name'] : 'Course'); ?>
      <?php endforeach; ?>
    </div>
  </section>

  <h2>Tasks</h2>
  <div class="toolbar">
    <div class="link-button">Grouped by Course</div>
    <div class="link-button">Filters</div>
  </div>

  <section class="grouped">
    <?php foreach ($courses as $course):
      $courseTasks = tasks_for_course($tasks, $course['id']); ?>
      <section class="panel course-panel">
        <div class="course-panel-header">
          <a class="course-label" href="course.php?id=<?php echo (int) $course['id']; ?>">
            <?php echo ($course['name']); ?>
          </a>
        </div>
        <div class="task-grid">
          <?php foreach ($courseTasks as $task):
            render_task_row($task);
            endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>
  </section>
</main>
<?php
render_shell_close();
?>
