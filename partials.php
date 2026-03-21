<?php
function render_head($title = 'T4SC') {
    echo "<!doctype html>\n";
    echo "<html lang='en'>\n";
    echo "<head>\n";
    echo "  <meta charset='utf-8'>\n";
    echo "  <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo "  <title>" . $title . "</title>\n";
    echo "  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>\n";
    echo "  <link rel='stylesheet' href='assets/style.css?v=3'>\n";
    echo "</head>\n";
    echo "<body class='bg-light'>\n";
}

function render_topbar() {
    echo "<nav class='navbar bg-white border-bottom sticky-top px-4'>\n";
    echo "  <a class='navbar-brand fw-bold' href='home.php'>T4SC</a>\n";
    echo "  <div class='d-flex gap-2'>\n";
    echo "    <a class='btn btn-outline-secondary btn-sm' href='settings.php'>Profile</a>\n";
    echo "  </div>\n";
    echo "</nav>\n";
}

function render_sidebar($active = 'home', $courses = [], $activeCourseId = 0) {
    $navItems = [
        'home'          => ['label' => 'Home',          'href' => 'home.php'],
        'not-completed' => ['label' => 'Not Completed', 'href' => 'not-completed.php'],
        'completed'     => ['label' => 'Completed',     'href' => 'completed.php'],
    ];

    echo "<aside class='bg-white border-end p-3' style='width:240px;flex-shrink:0;min-height:calc(100vh - 56px);'>\n";

    echo "  <p class='text-uppercase text-muted fw-semibold mb-2 px-1' style='font-size:11px;letter-spacing:.07em;'>Dashboard</p>\n";
    echo "  <div class='dropdown mb-1'>\n";
    echo "    <button class='btn btn-primary w-100 dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>+ New</button>\n";
    echo "    <ul class='dropdown-menu w-100'>\n";
    echo "      <li><a class='dropdown-item' href='course-new.php'>New Course</a></li>\n";
    echo "      <li><a class='dropdown-item' href='task-new.php'>New Task</a></li>\n";
    echo "    </ul>\n";
    echo "  </div>\n";

    echo "  <nav class='nav flex-column gap-1 mb-4'>\n";
    foreach ($navItems as $key => $item) {
        $cls = $active === $key ? ' active fw-medium' : '';
        echo "    <a class='nav-link rounded px-3 py-2$cls' href='{$item['href']}'>{$item['label']}</a>\n";
    }
    echo "  </nav>\n";

    echo "  <p class='text-uppercase text-muted fw-semibold mb-2 px-1' style='font-size:11px;letter-spacing:.07em;'>Courses</p>\n";
    echo "  <nav class='nav flex-column gap-1'>\n";
    foreach ($courses as $course) {
        $name = htmlspecialchars($course['name']);
        $cls  = ((int) $activeCourseId === (int) $course['id']) ? ' active fw-medium' : '';
        echo "    <a class='nav-link rounded px-3 py-2$cls' href='course.php?id={$course['id']}'>$name</a>\n";
    }
    echo "  </nav>\n";

    echo "</aside>\n";
}

function render_shell_open() {
    echo "<div class='d-flex'>\n";
}

function render_shell_close() {
    echo "</div>\n";
    echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>\n";
}

function render_sidebar_toggle() {
    // No-op: Bootstrap's dropdown handles the "+ New" toggle via data-bs-toggle automatically.
}

function render_task_row($task, $courseName = '') {
    $name     = $task['name'];
    $deadline = $task['deadline'];
    $priority = $task['priority'];
    $status   = $task['status'];
    $taskId   = (int) $task['id'];

    if ($priority === 'Low') {
        $badgeClass = 'badge-priority-low';
    } elseif ($priority === 'Medium') {
        $badgeClass = 'badge-priority-medium';
    } elseif ($priority === 'High') {
        $badgeClass = 'badge-priority-high';
    } else {
        $badgeClass = 'badge-priority-none';
    }

    $currentUrl   = htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'home.php', ENT_QUOTES);
    $today        = date('Y-m-d');
    $isDateFormat = is_string($deadline) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $deadline) === 1;
    $isOverdue    = $status !== 'Completed' && $isDateFormat && $deadline < $today;
    $rowClass     = $isOverdue ? ' table-danger' : '';
    $deadlineCls  = $isOverdue ? ' text-danger fw-semibold' : ' text-muted';
    $checked      = $status === 'Completed' ? "checked='checked'" : '';

    echo "<tr class='$rowClass'>\n";
    echo "  <td><a href='task.php?id=$taskId' class='fw-medium text-decoration-none link-dark'>" . htmlspecialchars($name) . "</a></td>\n";
    echo "  <td class='text-muted'>" . htmlspecialchars($courseName) . "</td>\n";
    echo "  <td class='$deadlineCls'>" . htmlspecialchars($deadline);
    if ($isOverdue) {
        echo " <span class='badge bg-danger rounded-pill ms-1' title='Overdue'>!</span>";
    }
    echo "</td>\n";
    echo "  <td><span class='badge $badgeClass'>" . htmlspecialchars($priority) . "</span></td>\n";
    echo "  <td>\n";
    echo "    <form method='post' action='task-toggle.php'>\n";
    echo "      <input type='hidden' name='task_id' value='$taskId'>\n";
    echo "      <input type='hidden' name='redirect' value=\"$currentUrl\">\n";
    echo "      <div class='form-check mb-0'>\n";
    echo "        <input class='form-check-input' type='checkbox' name='completed' value='1' $checked onchange='this.form.submit()' id='task-check-$taskId'>\n";
    echo "        <label class='form-check-label' for='task-check-$taskId'>" . htmlspecialchars($status) . "</label>\n";
    echo "      </div>\n";
    echo "    </form>\n";
    echo "  </td>\n";
    echo "</tr>\n";
}
?>
