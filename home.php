<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

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
  <h2><?php echo htmlspecialchars($demoUser['greeting']); ?>, <?php echo htmlspecialchars($displayName); ?>!</h2>
  <p class="text-muted mb-3">
    <strong class="text-dark">You have <?php echo count($dueToday); ?> task(s) due today.</strong>
  </p>

  <div class="card mb-4">
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
            <?php foreach ($dueToday as $task):
              $course = find_course($courses, $task['course_id']);
              render_task_row($task, $course ? $course['name'] : 'Course');
            endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <h2>Tasks</h2>
  <form class="row g-2 align-items-end mb-3" method="GET" action="home.php">
    <div class="col-auto">
      <label class="form-label fw-medium mb-1">Course</label>
      <select class="form-select" name="course">
        <option value="0">All courses</option>
        <?php foreach ($courses as $course): ?>
          <option value="<?php echo (int) $course['id']; ?>" <?php echo $filterCourseId === (int) $course['id'] ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($course['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="col-auto">
      <label class="form-label fw-medium mb-1">Sort</label>
      <select class="form-select" name="sort">
        <option value="">None</option>
        <option value="priority" <?php echo $filterSort === 'priority' ? 'selected' : ''; ?>>Priority</option>
        <option value="date" <?php echo $filterSort === 'date' ? 'selected' : ''; ?>>Date</option>
      </select>
    </div>

    <div class="col-auto">
      <label class="form-label fw-medium mb-1">Status</label>
      <select class="form-select" name="status">
        <option value="" <?php echo $filterStatus === '' ? 'selected' : ''; ?>>All</option>
        <option value="completed" <?php echo $filterStatus === 'completed' ? 'selected' : ''; ?>>Completed</option>
        <option value="not-completed" <?php echo $filterStatus === 'not-completed' ? 'selected' : ''; ?>>Not Completed</option>
      </select>
    </div>

    <div class="col-auto">
      <button class="btn btn-primary" type="submit">Apply</button>
    </div>
  </form>

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
            <?php foreach ($filteredTasks as $task):
              $course = find_course($courses, $task['course_id']);
              render_task_row($task, $course ? $course['name'] : '');
            endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
