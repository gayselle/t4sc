<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$errors = [];

$taskId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$task = null;

foreach ($tasks as $item) {
  if ($item['id'] === $taskId) {
    $task = $item;
    break;
  }
}

if (!$task) {
  $errors[] = 'Task not found or you do not have access to it.';
}

$task_name = $task ? $task['name'] : '';
$task_desc = $task ? $task['description'] : '';
$task_due = $task ? $task['deadline'] : '';
$task_priority = $task ? $task['priority'] : 'None';
$task_status = $task ? $task['status'] : 'Not Completed';
$task_course_id = $task ? (int) $task['course_id'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $task) {
  $task_name = trim($_POST['task_name'] ?? '');
  $task_desc = trim($_POST['task_desc'] ?? '');
  $task_due = $_POST['task_due'] ?? '';
  $task_priority = $_POST['task_priority'] ?? 'None';
  $task_status = $_POST['task_status'] ?? 'Not Completed';
  $task_course_id = isset($_POST['course_id']) ? (int) $_POST['course_id'] : 0;

  if ($task_name === '') {
    $errors[] = 'Task name is required.';
  }
  if ($task_course_id <= 0) {
    $errors[] = 'Please select a course.';
  }
  if ($task_due === '') {
    $errors[] = 'Please select a due date.';
  }

  if (empty($errors)) {
    if (!isset($_SESSION['user_id'])) {
      $errors[] = 'You must be logged in to edit a task.';
    } else {
      $userId = (int) $_SESSION['user_id'];

      $stmt = $pdo->prepare(
        'UPDATE task
         SET task_name = :task_name,
             task_desc = :task_desc,
             task_due = :task_due,
             task_priority = :task_priority,
             task_status = :task_status,
             course_id = :course_id
         WHERE task_id = :task_id AND user_id = :user_id'
      );

      try {
        $stmt->execute([
          ':task_name' => $task_name,
          ':task_desc' => $task_desc,
          ':task_due' => $task_due,
          ':task_priority' => $task_priority,
          ':task_status' => $task_status,
          ':course_id' => $task_course_id,
          ':task_id' => $taskId,
          ':user_id' => $userId,
        ]);

        header('Location: task.php?id=' . $taskId);
        exit;
      } catch (PDOException $e) {
        $errors[] = 'Could not update task. Please try again.';
      }
    }
  }
}

render_head('Edit Task');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <a class="crumb" href="javascript:history.back()">&times; Cancel</a>
  <h2>Edit Task</h2>

  <div class="card" style="max-width: 800px;">
    <div class="card-body">
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger py-2"><?php echo htmlspecialchars(implode(' ', $errors)); ?></div>
      <?php endif; ?>

      <?php if ($task): ?>
        <form method="post" action="task-edit.php?id=<?php echo (int) $taskId; ?>">
          <div class="mb-3">
            <label class="form-label fw-medium">Task Name</label>
            <input class="form-control" type="text" name="task_name" placeholder="Task Name" value="<?php echo htmlspecialchars($task_name); ?>" required>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Course</label>
              <select class="form-select" name="course_id" required>
                <option value="">Select a course</option>
                <?php foreach ($courses as $course): ?>
                  <option
                    value="<?php echo (int) $course['id']; ?>"
                    <?php echo $task_course_id === (int) $course['id'] ? 'selected' : ''; ?>
                  >
                    <?php echo htmlspecialchars($course['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Status</label>
              <select class="form-select" name="task_status">
                <option value="Not Completed" <?php echo $task_status === 'Not Completed' ? 'selected' : ''; ?>>Not Completed</option>
                <option value="Completed" <?php echo $task_status === 'Completed' ? 'selected' : ''; ?>>Completed</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-medium">Deadline</label>
              <input class="form-control" type="date" name="task_due" value="<?php echo htmlspecialchars($task_due); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-medium">Priority</label>
              <select class="form-select" name="task_priority">
                <option value="None" <?php echo $task_priority === 'None' ? 'selected' : ''; ?>>None</option>
                <option value="Low" <?php echo $task_priority === 'Low' ? 'selected' : ''; ?>>Low</option>
                <option value="Medium" <?php echo $task_priority === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                <option value="High" <?php echo $task_priority === 'High' ? 'selected' : ''; ?>>High</option>
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-medium">Task Description</label>
            <textarea class="form-control" name="task_desc" placeholder="Task Description" rows="5"><?php echo htmlspecialchars($task_desc); ?></textarea>
          </div>

          <div class="d-flex justify-content-end">
            <button class="btn btn-primary" type="submit">Save Changes</button>
          </div>
        </form>
      <?php endif; ?>
    </div>
  </div>
</main>
<?php
render_shell_close();
?>

