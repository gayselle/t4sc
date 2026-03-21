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
$overdueTasks = array_values(array_filter($tasks, function ($task) use ($today) {
    $deadline = $task['deadline'] ?? '';
    $status = $task['status'] ?? '';
    $isDateFormat = is_string($deadline) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $deadline) === 1;
    return $status !== 'Completed' && $isDateFormat && $deadline < $today;
}));
$overdueCount = count($overdueTasks);

render_head('T4SC Dashboard');
render_topbar();
render_shell_open();
render_sidebar('home', $courses);
render_sidebar_toggle();
?>

<main class="flex-grow-1 p-4 p-md-5 main-content">
  <h2 class="mb-1"><?php echo htmlspecialchars($demoUser['greeting']); ?>, <?php echo htmlspecialchars($displayName); ?>!</h2>
  <p class="text-muted mb-4">
    <strong class="text-dark">You have <?php echo count($dueToday); ?> tasks due today.</strong>
    <?php if ($overdueCount > 0): ?>
      <span class="overdue-count-badge"><?php echo $overdueCount; ?> overdue tasks</span>
    <?php endif; ?>
  </p>

  <div class="card shadow-sm mb-4">
    <div class="card-header fw-semibold">Due Today</div>
    <div class="card-body p-0">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr><th>Task</th><th>Course</th><th>Deadline</th><th>Priority</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($dueToday as $task): ?>
            <?php $course = find_course($courses, $task['course_id']); ?>
            <?php render_task_row($task, $course ? $course['name'] : 'Course'); ?>
          <?php endforeach; ?>
          <?php if (empty($dueToday)): ?>
            <tr><td colspan="5" class="text-center text-muted py-3">No tasks due today.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <h2 class="mb-3">All Tasks</h2>
  <form class="row g-3 align-items-end mb-4" method="GET" action="home.php">
    <div class="col-auto">
      <label class="form-label mb-1 fw-medium">Course</label>
      <select class="form-select form-select-sm" name="course">
        <option value="0">All courses</option>
        <?php foreach ($courses as $course): ?>
          <option value="<?php echo (int) $course['id']; ?>" <?php echo $filterCourseId === (int) $course['id'] ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($course['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-auto">
      <label class="form-label mb-1 fw-medium">Sort</label>
      <select class="form-select form-select-sm" name="sort">
        <option value="">None</option>
        <option value="priority" <?php echo $filterSort === 'priority' ? 'selected' : ''; ?>>Priority</option>
        <option value="date" <?php echo $filterSort === 'date' ? 'selected' : ''; ?>>Date</option>
      </select>
    </div>
    <div class="col-auto">
      <label class="form-label mb-1 fw-medium">Status</label>
      <select class="form-select form-select-sm" name="status">
        <option value="" <?php echo $filterStatus === '' ? 'selected' : ''; ?>>All</option>
        <option value="completed" <?php echo $filterStatus === 'completed' ? 'selected' : ''; ?>>Completed</option>
        <option value="not-completed" <?php echo $filterStatus === 'not-completed' ? 'selected' : ''; ?>>Not Completed</option>
      </select>
    </div>
    <div class="col-auto">
      <button type="submit" class="btn btn-outline-primary btn-sm">Apply</button>
    </div>
  </form>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr><th>Task</th><th>Course</th><th>Deadline</th><th>Priority</th><th>Status</th></tr>
        </thead>
        <tbody>
          <?php foreach ($filteredTasks as $task):
            $course = find_course($courses, $task['course_id']);
            render_task_row($task, $course ? $course['name'] : '');
          endforeach; ?>
          <?php if (empty($filteredTasks)): ?>
            <tr><td colspan="5" class="text-center text-muted py-3">No tasks found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>
