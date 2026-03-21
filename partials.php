<?php
//render recurring functions so that we don't have to write and edit those everytime for each of the pages
function render_head($title = 'T4SC') {
    echo "<!doctype html>\n";
    echo "<html lang='en'>\n";
    echo "<head>\n";
    echo "  <meta charset='utf-8'>\n";
    echo "  <meta name='viewport' content='width=device-width, initial-scale=1'>\n";
    echo "  <title>" . htmlspecialchars($title) . "</title>\n";
    echo "  <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'>\n";
    echo "  <link rel='stylesheet' href='assets/style.css?v=3'>\n";
    echo "</head>\n";
    echo "<body class='bg-light'>\n";
}

function render_topbar() {
    echo "<nav class='navbar bg-white border-bottom sticky-top px-4' style='z-index:100'>\n";
    echo "  <a class='navbar-brand fw-bold me-4' href='home.php'>T4SC</a>\n";
    echo "  <div class='flex-grow-1'>\n";
    echo "    <input type='search' class='form-control' placeholder='Search Tasks...'>\n";
    echo "  </div>\n";
    echo "  <div class='d-flex gap-2 ms-3'>\n";
    echo "    <span class='btn btn-outline-secondary btn-sm'>Profile</span>\n";
    echo "    <a class='btn btn-outline-secondary btn-sm' href='settings.php'>Settings</a>\n";
    echo "  </div>\n";
    echo "</nav>\n";
}

function render_sidebar($active = 'home', $courses = []) {
    $navItems = [
        'home'          => ['label' => 'Home',          'href' => 'home.php'],
        'not-completed' => ['label' => 'Not Completed', 'href' => 'not-completed.php'],
        'completed'     => ['label' => 'Completed',     'href' => 'completed.php'],
    ];

    echo "<nav class='sidebar bg-white border-end d-flex flex-column p-3'>\n";
    echo "  <p class='text-uppercase text-muted small fw-semibold mb-1 px-2 mt-1'>Dashboard</p>\n";
    echo "  <div class='position-relative mb-2'>\n";
    echo "    <button class='btn btn-primary w-100 sidebar-new-toggle' type='button'>+ New</button>\n";
    echo "    <div class='sidebar-new-menu dropdown-menu w-100 py-1 shadow-sm' data-open='false'>\n";
    echo "      <a class='dropdown-item' href='course-new.php'>New Course</a>\n";
    echo "      <a class='dropdown-item' href='task-new.php'>New Task</a>\n";
    echo "    </div>\n";
    echo "  </div>\n";

    foreach ($navItems as $key => $item) {
        $activeClass = $active === $key ? ' active' : '';
        echo "  <a class='nav-link rounded{$activeClass}' href='{$item['href']}'>{$item['label']}</a>\n";
    }

    echo "  <hr class='my-3'>\n";
    echo "  <p class='text-uppercase text-muted small fw-semibold mb-1 px-2'>Courses</p>\n";

    foreach ($courses as $course) {
        $name = htmlspecialchars($course['name']);
        echo "  <a class='nav-link rounded' href='course.php?id={$course['id']}'>$name</a>\n";
    }

    echo "</nav>\n";
}

function render_shell_open() {
    echo "<div class='app-shell d-flex'>\n";
}

function render_shell_close() {
    echo "</div>\n";
    echo "<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>\n";
}

function render_sidebar_toggle() {
    echo "<script>\n";
    echo "  var toggle = document.querySelector('.sidebar-new-toggle');\n";
    echo "  var menu = document.querySelector('.sidebar-new-menu');\n";
    echo "  if (toggle && menu) {\n";
    echo "    toggle.addEventListener('click', function () {\n";
    echo "      var currentlyOpen = menu.getAttribute('data-open') === 'true';\n";
    echo "      menu.setAttribute('data-open', currentlyOpen ? 'false' : 'true');\n";
    echo "    });\n";
    echo "    document.addEventListener('click', function (event) {\n";
    echo "      var clickedToggle = event.target === toggle;\n";
    echo "      var clickedInsideMenu = menu.contains(event.target);\n";
    echo "      if (!clickedToggle && !clickedInsideMenu) {\n";
    echo "        menu.setAttribute('data-open', 'false');\n";
    echo "      }\n";
    echo "    });\n";
    echo "  }\n";
    echo "</script>\n";
}

function render_task_row($task, $courseName = '') {
    $name     = htmlspecialchars($task['name']);
    $deadline = htmlspecialchars($task['deadline']);
    $priority = htmlspecialchars($task['priority']);
    $taskId   = (int) $task['id'];
    $course   = htmlspecialchars($courseName);

    $badgeClass = 'bg-secondary';
    if ($task['priority'] === 'High')       $badgeClass = 'bg-danger';
    elseif ($task['priority'] === 'Medium') $badgeClass = 'bg-warning text-dark';
    elseif ($task['priority'] === 'Low')    $badgeClass = 'bg-info text-dark';

    $checked     = $task['status'] === 'Completed' ? 'checked' : '';
    $statusBadge = $task['status'] === 'Completed'
        ? "<span class='badge bg-success'>Completed</span>"
        : "<span class='badge bg-secondary'>Pending</span>";

    $currentUrl = htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'home.php', ENT_QUOTES);

    echo "<tr>\n";
    echo "  <td><a href='task.php?id=$taskId' class='fw-medium link-dark text-decoration-none'>$name</a></td>\n";
    echo "  <td class='text-muted'>$course</td>\n";
    echo "  <td class='text-muted'>$deadline</td>\n";
    echo "  <td><span class='badge $badgeClass'>$priority</span></td>\n";
    echo "  <td>\n";
    echo "    <form method='post' action='task-toggle.php' class='d-flex align-items-center gap-2 mb-0'>\n";
    echo "      <input type='hidden' name='task_id' value='$taskId'>\n";
    echo "      <input type='hidden' name='redirect' value=\"$currentUrl\">\n";
    echo "      <input class='form-check-input mt-0' type='checkbox' name='completed' value='1' $checked onchange='this.form.submit()'>\n";
    echo "      $statusBadge\n";
    echo "    </form>\n";
    echo "  </td>\n";
    echo "</tr>\n";
}
?>
