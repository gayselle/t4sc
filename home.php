<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';

$displayName = isset($_SESSION['user_name']) && $_SESSION['user_name'] !== '' ? $_SESSION['user_name'] : $demoUser['name'];

// read filter values
$filterCourseId = isset($_GET['course']) ? (int) $_GET['course'] : 0;
$filterSort = $_GET['sort'] ?? '';
$filterStatus = $_GET['status'] ?? '';

// filtered task list (by course and status)
$filteredTasks = array_filter($tasks, function ($task) use ($filterCourseId, $filterStatus) {
    if ($filterCourseId > 0 && (int)$task['course_id'] !== (int)$filterCourseId) {
        return false;
    }
    if ($filterStatus === 'completed' && $task['status'] !== 'Completed') {
        return false;
    }
    if ($filterStatus === 'not-completed' && $task['status'] !== 'Not Completed') {
        return false;
    }
    return true;
});

// apply sort filters
if ($filterSort === 'priority') {
    // High -> Medium -> Low -> None
    $order = ['High' => 1, 'Medium' => 2, 'Low' => 3, 'None' => 4];
    usort($filteredTasks, function ($a, $b) use ($order) {
        $pa = $order[$a['priority']] ?? 99;
        $pb = $order[$b['priority']] ?? 99;
        return $pa <=> $pb;
    });
} elseif ($filterSort === 'date') {
    // most recent due date first
    usort($filteredTasks, function ($a, $b) {
        return strcmp($a['deadline'], $b['deadline']);
    });
}

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
  <h2><?php echo ($demoUser['greeting']); ?>, <?php echo ($displayName); ?>!</h2>
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
  <form class="toolbar task-filters" method="GET" action="home.php">
    <label class="labelled">
      Course
      <select class="filter" name="course">
        <option value="0">All courses</option>
        <?php foreach ($courses as $course): ?>
          <option value="<?php echo (int) $course['id']; ?>" <?php echo $filterCourseId === (int) $course['id'] ? 'selected' : ''; ?>>
            <?php echo ($course['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label class="labelled">
      Sort
      <select class="filter" name="sort">
        <option value="">None</option>
        <option value="priority" <?php echo $filterSort === 'priority' ? 'selected' : ''; ?>>Priority</option>
        <option value="date" <?php echo $filterSort === 'date' ? 'selected' : ''; ?>>Date</option>
      </select>
    </label>

    <label class="labelled">
      Status
      <select class="filter" name="status">
        <option value="" <?php echo $filterStatus === '' ? 'selected' : ''; ?>>All</option>
        <option value="completed" <?php echo $filterStatus === 'completed' ? 'selected' : ''; ?>>Completed</option>
        <option value="not-completed" <?php echo $filterStatus === 'not-completed' ? 'selected' : ''; ?>>Not Completed</option>
      </select>
    </label>

    <button class="link-button filters-apply" type="submit">Apply</button>
  </form>

  <section class="panel" style="margin-top:24px;">
    <div class="task-grid">
      <?php foreach ($filteredTasks as $task):
        $course = find_course($courses, $task['course_id']);
        render_task_row($task, $course ? $course['name'] : '');
      endforeach; ?>
    </div>
  </section>
</main>
<?php
render_shell_close();
?>
