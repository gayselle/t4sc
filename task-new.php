<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/data.php';
require_once __DIR__ . '/partials.php';
require_login();

$errors = [];
$task_name = '';
$task_desc = '';
$task_due = '';
$task_priority = 'None';
$task_status = 'Not Completed';
$task_course_id = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
      $errors[] = 'You must be logged in to create a task.';
    } else {
      $userId = (int) $_SESSION['user_id'];

      $stmt = $pdo->prepare(
        'INSERT INTO task (task_name, task_desc, task_due, task_priority, task_status, course_id, user_id)
         VALUES (:task_name, :task_desc, :task_due, :task_priority, :task_status, :course_id, :user_id)'
      );

      try {
        $stmt->execute([
          ':task_name' => $task_name,
          ':task_desc' => $task_desc,
          ':task_due' => $task_due,
          ':task_priority' => $task_priority,
          ':task_status' => $task_status,
          ':course_id' => $task_course_id,
          ':user_id' => $userId,
        ]);

        $newTaskId = (int) $pdo->lastInsertId();
        header('Location: task.php?id=' . $newTaskId);
        exit;
      } catch (PDOException $e) {
        $errors[] = 'Could not save task. Please try again.';
      }
    }
  }
}

render_head('New Task');
render_topbar();
render_shell_open();
render_sidebar('', $courses);
render_sidebar_toggle();
?>
<main class="main">
  <a class="crumb" href="javascript:history.back()">&times; Cancel</a>
  <h2 class="form-title">New Task</h2>

  <section class="panel form-panel">
    <?php if (!empty($errors)): ?>
      <p style="margin-bottom:16px; color:#b91c1c; text-align:left; font-size:14px;">
        <?php echo htmlspecialchars(implode(' ', $errors)); ?>
      </p>
    <?php endif; ?>

    <form class="form-grid" method="post" action="task-new.php">
      <label class="labelled">
        Task Name
        <input
          type="text"
          name="task_name"
          placeholder="Task Name"
          value="<?php echo htmlspecialchars($task_name); ?>"
          required
        >
      </label>

      <div class="form-row">
        <label class="labelled">
          Course
          <select name="course_id" required>
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
        </label>
        
        <label class="labelled">
          Status
          <select name="task_status">
            <option value="Not Completed" <?php echo $task_status === 'Not Completed' ? 'selected' : ''; ?>>Not Completed</option>
            <option value="Completed" <?php echo $task_status === 'Completed' ? 'selected' : ''; ?>>Completed</option>
          </select>
        </label>
      </div>

      <div class="form-row">
        <label class="labelled">
          Deadline
          <input
            type="date"
            name="task_due"
            value="<?php echo htmlspecialchars($task_due); ?>"
            required
          >
        </label>
        <label class="labelled">
          Priority
          <select name="task_priority">
            <option value="None" <?php echo $task_priority === 'None' ? 'selected' : ''; ?>>None</option>
            <option value="Low" <?php echo $task_priority === 'Low' ? 'selected' : ''; ?>>Low</option>
            <option value="Medium" <?php echo $task_priority === 'None' ? 'selected' : ''; ?>>Medium</option>
            <option value="High" <?php echo $task_priority === 'High' ? 'selected' : ''; ?>>High</option>
          </select>
        </label>
      </div>

      <label class="labelled">
        Task Description
        <textarea
          name="task_desc"
          placeholder="Task Description"
        ><?php echo htmlspecialchars($task_desc); ?></textarea>
      </label>

      <div class="form-actions">
        <button class="solid" type="submit">Save</button>
      </div>
    </form>
  </section>
</main>
<?php
render_shell_close();
?>
